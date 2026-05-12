<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intrant;
use App\Models\IntrantStock;
use App\Models\IntrantMouvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IntrantController extends Controller
{
    public function index()
    {
        $intrants = Intrant::with('stocks')->orderBy('nom')->paginate(15);
        return view('admin.intrants.index', compact('intrants'));
    }

    public function create()
    {
        return view('admin.intrants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:engrais,pesticide,herbicide,semence,autre',
            'unite' => 'required|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $validated['code_intrant'] = 'INT-' . str_pad(Intrant::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['est_actif'] = true;

        $intrant = Intrant::create($validated);

        // Créer le stock initial pour les 3 zones
        $zones = ['Centrale', 'Kara', 'Savanes'];
        foreach ($zones as $zone) {
            IntrantStock::create([
                'intrant_id' => $intrant->id,
                'zone' => $zone,
                'stock_actuel' => 0,
                'seuil_alerte' => 50,
                'unite' => $validated['unite']
            ]);
        }

        return redirect()->route('admin.intrants.index')
            ->with('success', 'Intrant créé avec succès');
    }

    public function show($id)
    {
        $intrant = Intrant::with(['stocks.mouvements'])->findOrFail($id);
        return view('admin.intrants.show', compact('intrant'));
    }

    public function edit($id)
    {
        $intrant = Intrant::findOrFail($id);
        return view('admin.intrants.edit', compact('intrant'));
    }

    public function update(Request $request, $id)
    {
    $intrant = Intrant::findOrFail($id);

    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'type' => 'required|in:engrais,pesticide,herbicide,semence,autre',
        'unite' => 'required|string|max:50',
        'prix_unitaire' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'est_actif' => 'boolean',
        'stocks' => 'array',
        'stocks.*.seuil_alerte' => 'required|numeric|min:0',
        'stocks.*.emplacement' => 'nullable|string'
    ]);

    $intrant->update($validated);

    // Mettre à jour les seuils et emplacements par zone
    if (isset($validated['stocks'])) {
        foreach ($validated['stocks'] as $zone => $data) {
            $stock = IntrantStock::where('intrant_id', $intrant->id)
                ->where('zone', $zone)
                ->first();
            
            if ($stock) {
                $stock->update([
                    'seuil_alerte' => $data['seuil_alerte'],
                    'emplacement' => $data['emplacement'] ?? null
                ]);
            }
        }
    }

    return redirect()->route('admin.intrants.index')
        ->with('success', 'Intrant mis à jour avec succès');
    }

    public function destroy($id)
    {
        $intrant = Intrant::findOrFail($id);
        
        if ($intrant->stocks()->where('stock_actuel', '>', 0)->exists()) {
            return back()->with('error', 'Impossible de supprimer un intrant qui a du stock');
        }
        
        $intrant->delete();
        return redirect()->route('admin.intrants.index')
            ->with('success', 'Intrant supprimé avec succès');
    }

    // Gestion des stocks par zone
    public function stock($id, $zone)
    {
        $stock = IntrantStock::where('intrant_id', $id)
            ->where('zone', $zone)
            ->firstOrFail();
        
        $mouvements = $stock->mouvements()->with('user')->orderBy('created_at', 'desc')->paginate(20);
        
        // Récupérer toutes les zones pour le transfert
        $zones = IntrantStock::where('intrant_id', $id)
            ->where('zone', '!=', $zone)
            ->get();
        
        return view('admin.intrants.stock', compact('stock', 'mouvements', 'zones'));
    }

    public function ajouterStock(Request $request, $id, $zone)
    {
        $stock = IntrantStock::where('intrant_id', $id)
            ->where('zone', $zone)
            ->firstOrFail();

        $validated = $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'motif' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $stock->stock_actuel += $validated['quantite'];
            $stock->save();

            IntrantMouvement::create([
                'intrant_stock_id' => $stock->id,
                'type' => 'entree',
                'quantite' => $validated['quantite'],
                'motif' => $validated['motif'],
                'reference' => $validated['reference'],
                'user_id' => auth()->guard('admin')->id(),
                'notes' => $validated['notes']
            ]);

            DB::commit();

            return back()->with('success', 'Stock ajouté avec succès. Nouveau stock: ' . $stock->stock_actuel . ' ' . $stock->unite);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'ajout du stock');
        }
    }

    public function retirerStock(Request $request, $id, $zone)
    {
        $stock = IntrantStock::where('intrant_id', $id)
            ->where('zone', $zone)
            ->firstOrFail();

        $validated = $request->validate([
            'quantite' => 'required|numeric|min:0.01|max:' . $stock->stock_actuel,
            'motif' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $stock->stock_actuel -= $validated['quantite'];
            $stock->save();

            IntrantMouvement::create([
                'intrant_stock_id' => $stock->id,
                'type' => 'sortie',
                'quantite' => $validated['quantite'],
                'motif' => $validated['motif'],
                'reference' => $validated['reference'],
                'user_id' => auth()->guard('admin')->id(),
                'notes' => $validated['notes']
            ]);

            DB::commit();

            return back()->with('success', 'Stock retiré avec succès. Nouveau stock: ' . $stock->stock_actuel . ' ' . $stock->unite);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors du retrait du stock');
        }
    }

    public function alerte()
    {
        $stocksCritiques = IntrantStock::with('intrant')
            ->whereRaw('stock_actuel <= seuil_alerte')
            ->get();
        
        // Récupérer les zones distinctes pour le filtre
        $zones = IntrantStock::distinct()->pluck('zone');
        
        return view('admin.intrants.alertes', compact('stocksCritiques', 'zones'));
    }

    public function dashboard()
    {
        // Calcul des entrées et sorties du mois
        $entreesMois = IntrantMouvement::where('type', 'entree')
            ->whereMonth('created_at', now()->month)
            ->sum('quantite');
        
        $sortiesMois = IntrantMouvement::where('type', 'sortie')
            ->whereMonth('created_at', now()->month)
            ->sum('quantite');
        
        // Taux d'alerte
        $totalIntrants = Intrant::count();
        $nbAlertes = IntrantStock::whereRaw('stock_actuel <= seuil_alerte')->count();
        $tauxAlerte = $totalIntrants > 0 ? round(($nbAlertes / $totalIntrants) * 100, 1) : 0;
        
        // Rotation du stock (estimation)
        $sortiesAnnuelles = IntrantMouvement::where('type', 'sortie')
            ->whereYear('created_at', now()->year)
            ->sum('quantite');
        $stockMoyen = IntrantStock::avg('stock_actuel');
        $rotationStock = $stockMoyen > 0 ? round($sortiesAnnuelles / $stockMoyen, 1) : 0;
        
        $stats = [
            'total_stock' => IntrantStock::sum('stock_actuel'),
            'valeur_stock' => IntrantStock::join('intrants', 'intrant_stocks.intrant_id', '=', 'intrants.id')
                ->sum(\DB::raw('intrant_stocks.stock_actuel * intrants.prix_unitaire')),
            'alertes' => $nbAlertes,
            'taux_alerte' => $tauxAlerte,
            'rotation_stock' => $rotationStock,
            'entrees_mois' => $entreesMois,
            'sorties_mois' => $sortiesMois,
        ];
        
        $stocksParZone = IntrantStock::select('zone', \DB::raw('SUM(stock_actuel) as total'))
            ->groupBy('zone')
            ->get();
        
        $alertesParZone = IntrantStock::select('zone', \DB::raw('COUNT(*) as nb_alertes'))
            ->whereRaw('stock_actuel <= seuil_alerte')
            ->groupBy('zone')
            ->get();
        
        $topSorties = IntrantMouvement::select('intrant_stock_id', \DB::raw('SUM(quantite) as total_quantite'))
            ->where('type', 'sortie')
            ->whereMonth('created_at', now()->month)
            ->groupBy('intrant_stock_id')
            ->with('stock.intrant')
            ->orderBy('total_quantite', 'desc')
            ->limit(5)
            ->get()
            ->filter(function($item) { return $item->stock && $item->stock->intrant; });
        
        $activitesRecentes = IntrantMouvement::with(['stock.intrant', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.intrants.dashboard', compact('stats', 'stocksParZone', 'alertesParZone', 'topSorties', 'activitesRecentes'));
    }

    /**
     * Transférer du stock d'une zone à une autre
     */
    public function transferer(Request $request, $intrantId)
    {
        $validated = $request->validate([
            'source_zone' => 'required|string',
            'destination_zone' => 'required|string|different:source_zone',
            'quantite' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);
        
        // Récupérer les stocks sources et destination
        $sourceStock = IntrantStock::where('intrant_id', $intrantId)
            ->where('zone', $validated['source_zone'])
            ->firstOrFail();
        
        if ($sourceStock->stock_actuel < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant dans la zone source.');
        }
        
        $destinationStock = IntrantStock::firstOrCreate(
            ['intrant_id' => $intrantId, 'zone' => $validated['destination_zone']],
            ['stock_actuel' => 0, 'seuil_alerte' => 100, 'unite' => $sourceStock->unite]
        );
        
        DB::beginTransaction();
        try {
            // Sortie de la zone source
            $sourceStock->stock_actuel -= $validated['quantite'];
            $sourceStock->save();
            
            IntrantMouvement::create([
                'intrant_stock_id' => $sourceStock->id,
                'type' => 'sortie',
                'quantite' => $validated['quantite'],
                'motif' => 'Transfert',
                'reference' => 'Transfert vers ' . $validated['destination_zone'],
                'user_id' => auth()->guard('admin')->id(),
                'notes' => $validated['notes']
            ]);
            
            // Entrée dans la zone destination
            $destinationStock->stock_actuel += $validated['quantite'];
            $destinationStock->save();
            
            IntrantMouvement::create([
                'intrant_stock_id' => $destinationStock->id,
                'type' => 'entree',
                'quantite' => $validated['quantite'],
                'motif' => 'Transfert',
                'reference' => 'Transfert depuis ' . $validated['source_zone'],
                'user_id' => auth()->guard('admin')->id(),
                'notes' => $validated['notes']
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.intrants.stock', ['intrant' => $intrantId, 'zone' => $validated['source_zone']])
                ->with('success', " {$validated['quantite']} {$sourceStock->unite} transféré(s) de {$validated['source_zone']} vers {$validated['destination_zone']}");
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors du transfert: ' . $e->getMessage());
        }
    }

    /**
 * Données d'évolution pour le graphique
 */
public function evolutionData(Request $request)
{
    $months = (int) $request->get('months', 6);
    $data = [];
    $labels = [];
    
    for ($i = $months - 1; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $labels[] = $date->format('M Y');
        
        $valeur = IntrantMouvement::whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->get()
            ->sum(function($mvt) {
                $prix = $mvt->stock->intrant->prix_unitaire ?? 0;
                return $mvt->type == 'entree' ? $mvt->quantite * $prix : -($mvt->quantite * $prix);
            });
        
        $data[] = $valeur;
    }
    
    return response()->json([
        'labels' => $labels,
        'values' => $data
    ]);
}

/**
 * Générer un rapport PDF
 */
public function rapportPdf()
{
    $intrants = Intrant::with('stocks')->get();
    $date = now()->format('d/m/Y H:i');
    
    // Calcul des totaux
    $valeurTotale = 0;
    foreach ($intrants as $intrant) {
        $stockTotal = $intrant->stocks->sum('stock_actuel');
        $valeurTotale += $stockTotal * $intrant->prix_unitaire;
    }
    
    $pdf = PDF::loadView('admin.intrants.rapport-pdf', compact('intrants', 'date', 'valeurTotale'));
    return $pdf->download('rapport-stocks-' . now()->format('Y-m-d') . '.pdf');
}

    public function apiShow($id)
    {
    $intrant = Intrant::findOrFail($id);
    return response()->json([
        'id' => $intrant->id,
        'nom' => $intrant->nom,
        'code_intrant' => $intrant->code_intrant,
        'type' => $intrant->type,
        'unite' => $intrant->unite,
        'prix_unitaire' => $intrant->prix_unitaire
    ]);
    }
}