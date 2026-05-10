<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achat;
use App\Models\Collecte;
use App\Exports\achatsExport;
use App\Models\Producteur;
use App\Models\Bordereau;
use App\Traits\DatabaseCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchatController extends Controller
{
    use DatabaseCompatibility;
    /**
     * Liste des achats
     */
    public function index(Request $request)
    {
        $query = Achat::with('collecte.producteur');
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $achats = $query->orderBy('date_achat', 'desc')->paginate(15);
        return view('admin.achats.index', compact('achats'));
    }
    
    /**
     * Formulaire de création
     */
    public function create()
    {
         //On récupère les collectes qui n'ont pas encore d'achat associé
        $collectes = Collecte::with('producteur')
            ->whereDoesntHave('achat')
            ->orderBy('date_collecte', 'desc')
            ->get();
        
        return view('admin.achats.create', compact('collectes'));
    }
    
    /**
 * Afficher le formulaire d'édition
 */
public function edit($id)
{
    $achat = Achat::findOrFail($id);
    return view('admin.achats.edit', compact('achat'));
}

/**
 * Mettre à jour un achat
 */
public function update(Request $request, $id)
{
    $achat = Achat::findOrFail($id);
    
    $validated = $request->validate([
        'date_achat' => 'required|date',
        'acheteur' => 'required|string|max:255',
        'quantite' => 'required|numeric|min:0',
        'prix_achat' => 'required|numeric|min:0',
        'mode_paiement' => 'required|in:especes,virement,cheque,mobile_money',
        'statut' => 'required|in:confirme,en_attente,annule',
        'reference_facture' => 'nullable|string',
        'notes' => 'nullable|string'
    ]);
    
    $validated['montant_total'] = $validated['quantite'] * $validated['prix_achat'];
    
    $achat->update($validated);
    
    return redirect()->route('admin.achats.index')
        ->with('success', 'Achat modifié avec succès');
}

/**
 * Dashboard des achats
 */
public function dashboard()
{
    $stats = [
        'total_achats' => Achat::count(),
        'montant_total' => Achat::sum('montant_total'),
        'quantite_totale' => Achat::sum('quantite'),
        'achats_mois' => Achat::whereMonth('date_achat', now()->month)->count(),
    ];
    
    $achats_par_produit = Achat::select('collecte_id', DB::raw('SUM(quantite) as total'))
        ->with('collecte')
        ->groupBy('collecte_id')
        ->get()
        ->map(function($item) {
            return (object)[
                'produit' => $item->collecte->produit,
                'total' => $item->total
            ];
        });
    
    $achats_par_paiement = Achat::select('mode_paiement', DB::raw('count(*) as total'), DB::raw('SUM(montant_total) as montant'))
        ->groupBy('mode_paiement')
        ->get();
    
    $achats_recents = Achat::with('collecte.producteur')
        ->orderBy('date_achat', 'desc')
        ->limit(10)
        ->get();
    
      // Utiliser la méthode compatible
        $evolution = Achat::select(
            DB::raw($this->getDateFormatFunction('date_achat', '%Y-%m') . ' as mois'),
            DB::raw('SUM(quantite) as total_quantite'),
            DB::raw('SUM(montant_total) as total_montant')
        )
        ->groupBy('mois')
        ->orderBy('mois', 'desc')
        ->limit(6)
        ->get()
        ->reverse();

    return view('admin.achats.dashboard', compact('stats', 'achats_par_produit', 'achats_par_paiement', 'achats_recents', 'evolution'));
}

    /**
     * Enregistrer un achat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collecte_id' => 'required|exists:collectes,id',
            'date_achat' => 'required|date',
            'acheteur' => 'required|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:especes,virement,cheque,mobile_money',
            'reference_facture' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
         //On vérifie que la quantité ne dépasse pas celle de la collecte
         $collecte = Collecte::find($validated['collecte_id']);
         if ($validated['quantite'] > $collecte->quantite_nette) {
             return back()->with('error', 'La quantité achetée ne peut pas dépasser la quantité collectée.')
                 ->withInput();
         }
         
         //On vérifie que la collecte n'a pas déjà un achat
         if ($collecte->achat) {
             return back()->with('error', 'Cette collecte a déjà un achat associé.')
                 ->withInput();
         }

        $validated['code_achat'] = 'ACH-' . str_pad(Achat::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['montant_total'] = $validated['quantite'] * $validated['prix_achat'];
        $validated['statut'] = 'confirme';

        // Générer la référence facture si non fournie
        if (empty($validated['reference_facture'])) {
            $date = date('Ymd');
            $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $validated['reference_facture'] = 'FAC-' . $date . '-' . $random;
        }
        
        Achat::create($validated);
        
        return redirect()->route('admin.achats.index')
            ->with('success', 'Achat enregistré avec succès');
    }
    
    /**
     * Afficher un achat
     */
    public function show($id)
    {
        $achat = Achat::with('collecte.producteur')->findOrFail($id);
        return view('admin.achats.show', compact('achat'));
    }
    
    /**
     * Valider un achat
     */
    public function valider($id)
    {
        $achat = Achat::findOrFail($id);
        $achat->update(['statut' => 'confirme']);
        
        return back()->with('success', 'Achat validé avec succès');
    }
    
    /**
     * Supprimer un achat
     */
    public function destroy($id)
    {
        $achat = Achat::findOrFail($id);
        $achat->delete();
        
        return redirect()->route('admin.achats.index')
            ->with('success', 'Achat supprimé avec succès');
    }
    
    /**
     * Exporter les achats
     */
    public function export()
    {
        $achats = Achat::with('collecte.producteur')->get();
        
        $csv = "Code,Date,Producteur,Produit,Quantité,Prix unitaire,Montant total,Acheteur,Paiement,Statut\n";
        
        foreach ($achats as $a) {
            $csv .= "{$a->code_achat},{$a->date_achat},{$a->collecte->producteur->nom_complet},{$a->collecte->produit},{$a->quantite},{$a->prix_achat},{$a->montant_total},{$a->acheteur},{$a->mode_paiement},{$a->statut}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="achats.csv"');
    }

    
/**
 * Générer un bordereau d'achat dédié (impression)
 */
public function printBordereau($id)
{
    $achat = Achat::with(['collecte.producteur'])->findOrFail($id);
    
    $contenu = [
        'numero_achat' => $achat->code_achat,
        'date_achat' => $achat->date_achat->format('d/m/Y'),
        'acheteur' => $achat->acheteur,
        'mode_paiement' => $achat->mode_paiement,
        'reference_facture' => $achat->reference_facture,
        'statut' => $achat->statut,
        'vendeur' => [
            'nom' => $achat->collecte->producteur->nom_complet,
            'code' => $achat->collecte->producteur->code_producteur,
            'contact' => $achat->collecte->producteur->contact,
            'localisation' => $achat->collecte->producteur->localisation
        ],
        'produits' => [
            [
                'nom' => $achat->collecte->produit,
                'quantite' => $achat->quantite,
                'prix_unitaire' => $achat->prix_achat,
                'montant' => $achat->montant_total
            ]
        ],
        'quantite_totale' => $achat->quantite,
        'montant_total' => $achat->montant_total,
        'observations' => $achat->notes
    ];

    $bordereau = Bordereau::create([
        'code_bordereau' => 'BRD-ACH-' . str_pad(Bordereau::count() + 1, 6, '0', STR_PAD_LEFT),
        'type' => 'achat',
        'reference_id' => $achat->id,
        'reference_type' => Achat::class,
        'date_emission' => now(),
        'contenu' => $contenu,
        'emetteur' => auth()->guard('admin')->user()->nom ?? 'Administrateur',
        'destinataire' => $achat->collecte->producteur->nom_complet,
        'statut' => 'valide'
    ]);

    return view('admin.achats.print-bordereau', compact('achat', 'bordereau'))
        ->with('success', 'Bordereau d\'achat généré avec succès');
}
}