<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProducteursExport;
use App\Exports\CreditsExport;
use App\Exports\CollectesExport;
use App\Exports\achatsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use App\Models\DistributionSemence;
use App\Models\Remboursement;
use App\Models\Cooperative;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    /**
     * Dashboard principal des rapports
     */
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_producteurs' => Producteur::count(),
            'producteurs_actifs' => Producteur::where('statut', 'actif')->count(),
            'total_cooperatives' => Cooperative::count(),
            'total_credits' => CreditAgricole::sum('montant_total'),
            'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'taux_remboursement' => $this->calculerTauxRemboursementGlobal(),
            'total_collecte' => Collecte::sum('quantite_nette'),
            'valeur_totale' => Collecte::sum('montant_total'),
            'collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
            'valeur_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('montant_total'),
        ];

        // Graphiques
        $collectes_par_mois = $this->getCollectesParMois();
        $credits_par_statut = CreditAgricole::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();
        $producteurs_par_region = Producteur::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();

        return view('admin.rapports.index', compact('stats', 'collectes_par_mois', 'credits_par_statut', 'producteurs_par_region'));
    }

    /**
     * Rapport financier
     */
    public function financier(Request $request)
    {
        $periode = $request->periode ?? 'mois';
        
        switch ($periode) {
            case 'mois':
                $date_debut = now()->startOfMonth();
                $date_fin = now()->endOfMonth();
                break;
            case 'trimestre':
                $date_debut = now()->startOfQuarter();
                $date_fin = now()->endOfQuarter();
                break;
            case 'annee':
                $date_debut = now()->startOfYear();
                $date_fin = now()->endOfYear();
                break;
            default:
                $date_debut = now()->subDays(30);
                $date_fin = now();
        }

        // Crédits accordés sur la période
        $credits_accordes = CreditAgricole::whereBetween('date_octroi', [$date_debut, $date_fin])
            ->sum('montant_total');
        
        // Remboursements sur la période
        $remboursements_periode = Remboursement::whereBetween('date_remboursement', [$date_debut, $date_fin])
            ->sum('montant');
        
        // Collectes sur la période
        $collectes = Collecte::whereBetween('date_collecte', [$date_debut, $date_fin]);
        $collectes_montant = $collectes->sum('montant_total');
        $collectes_quantite = $collectes->sum('quantite_nette');
        
        // Top producteurs
        $top_producteurs = Producteur::withSum('collectes', 'montant_total')
            ->orderBy('collectes_sum_montant_total', 'desc')
            ->limit(10)
            ->get();

        $rapport = [
            'periode' => $periode,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'credits_accordes' => $credits_accordes,
            'remboursements' => $remboursements_periode,
            'collectes_montant' => $collectes_montant,
            'collectes_quantite' => $collectes_quantite,
            'taux_recouvrement' => $credits_accordes > 0 ? ($remboursements_periode / $credits_accordes) * 100 : 0,
        ];

        if ($request->export == 'pdf') {
            return $this->exportPDF('admin.rapports.exports.financier-pdf', $rapport, 'rapport-financier-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.rapports.financier', compact('rapport', 'top_producteurs'));
    }

    /**
     * Rapport production
     */
    public function production(Request $request)
    {
        $produit = $request->produit;
        $date_debut = $request->date_debut ?? now()->startOfYear()->format('Y-m-d');
        $date_fin = $request->date_fin ?? now()->format('Y-m-d');

        $query = Collecte::whereBetween('date_collecte', [$date_debut, $date_fin]);
        
        if ($produit) {
            $query->where('produit', $produit);
        }

        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(20);
        
        $produits_liste = Collecte::distinct()->pluck('produit');
        
        $stats = [
            'total_quantite' => $query->sum('quantite_nette'),
            'total_valeur' => $query->sum('montant_total'),
            'nb_collectes' => $query->count(),
            'nb_producteurs' => $query->distinct('producteur_id')->count('producteur_id'),
        ];

        // Top producteurs
        $top_producteurs = Collecte::select('producteur_id', DB::raw('SUM(quantite_nette) as total_quantite'), DB::raw('SUM(montant_total) as total_montant'))
            ->whereBetween('date_collecte', [$date_debut, $date_fin])
            ->when($produit, fn($q) => $q->where('produit', $produit))
            ->with('producteur')
            ->groupBy('producteur_id')
            ->orderBy('total_quantite', 'desc')
            ->limit(10)
            ->get();

        if ($request->export == 'pdf') {
            $data = compact('collectes', 'stats', 'produit', 'date_debut', 'date_fin', 'top_producteurs');
            return $this->exportPDF('admin.rapports.exports.production-pdf', $data, 'rapport-production-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.rapports.production', compact('collectes', 'stats', 'produits_liste', 'produit', 'date_debut', 'date_fin', 'top_producteurs'));
    }

    /**
     * Rapport crédits
     */
    public function credits(Request $request)
    {
        $statut = $request->statut;
        
        $query = CreditAgricole::with(['producteur', 'cooperative']);
        
        if ($statut) {
            $query->where('statut', $statut);
        }

        $credits = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total_credits' => CreditAgricole::sum('montant_total'),
            'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'nb_credits' => CreditAgricole::count(),
            'nb_credits_actifs' => CreditAgricole::where('statut', 'actif')->count(),
            'taux_remboursement' => $this->calculerTauxRemboursementGlobal(),
        ];

        if ($request->export == 'pdf') {
            return $this->exportPDF('admin.rapports.exports.credits-pdf', compact('credits', 'stats'), 'rapport-credits-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.rapports.credits', compact('credits', 'stats', 'statut'));
    }

    /**
     * Rapport producteurs
     */
    public function producteurs(Request $request)
    {
        $region = $request->region;
        $statut = $request->statut;
        
        $query = Producteur::with('cooperative');
        
        if ($region) {
            $query->where('region', $region);
        }
        if ($statut) {
            $query->where('statut', $statut);
        }

        $producteurs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Producteur::count(),
            'par_region' => Producteur::select('region', DB::raw('count(*) as total'))
                ->groupBy('region')
                ->get(),
            'total_superficie' => Producteur::sum('superficie_totale'),
            'producteurs_actifs' => Producteur::where('statut', 'actif')->count(),
        ];

        if ($request->export == 'pdf') {
            return $this->exportPDF('admin.rapports.exports.producteurs-pdf', compact('producteurs', 'stats'), 'rapport-producteurs-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.rapports.producteurs', compact('producteurs', 'stats', 'region', 'statut'));
    }

    /**
     * Rapport stocks
     */
    public function stocks(Request $request)
    {
        $zone = $request->zone;
        
        $query = Stock::query();
        
        if ($zone) {
            $query->where('zone', $zone);
        }

        $stocks = $query->orderBy('produit')->get();
        
        $zones = Stock::distinct()->pluck('zone');
        
        $stats = [
            'total_stock' => $stocks->sum('stock_actuel'),
            'valeur_totale' => $stocks->sum('stock_actuel') * 500,
            'nb_produits' => $stocks->count(),
            'alertes' => $stocks->where('stock_actuel', '<=', 'seuil_alerte')->count(),
        ];

        if ($request->export == 'pdf') {
            return $this->exportPDF('admin.rapports.exports.stocks-pdf', compact('stocks', 'stats'), 'rapport-stocks-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.rapports.stocks', compact('stocks', 'stats', 'zones', 'zone'));
    }
    
    public function exportExcel($type, Request $request)
    {
        switch ($type) {
            case 'producteurs':
                $export = new ProducteursExport($request->region, $request->statut);
                return Excel::download($export, 'producteurs-' . date('Y-m-d') . '.xlsx');
            case 'credits':
                $export = new CreditsExport($request->statut);
                return Excel::download($export, 'credits-' . date('Y-m-d') . '.xlsx');
            case 'collectes':
                $export = new CollectesExport($request->produit, $request->date_debut, $request->date_fin);
                return Excel::download($export, 'collectes-' . date('Y-m-d') . '.xlsx');
            case 'achats':
                $export = new achatsExport($request->statut);
                return Excel::download($export, 'achats-' . date('Y-m-d') . '.xlsx');
            default:
                return back()->with('error', 'Type d\'export non disponible');
        }
    }

    /**
     * Calculer le taux de remboursement global
     */
    private function calculerTauxRemboursementGlobal()
    {
        $total_credits = CreditAgricole::sum('montant_total');
        $total_rembourse = CreditAgricole::sum('montant_total') - CreditAgricole::sum('montant_restant');
        
        return $total_credits > 0 ? ($total_rembourse / $total_credits) * 100 : 0;
    }

    /**
     * Collectes par mois
     */
    private function getCollectesParMois()
    {
        return Collecte::select(
                DB::raw('strftime("%Y-%m", date_collecte) as mois'),
                DB::raw('SUM(quantite_nette) as total_quantite'),
                DB::raw('SUM(montant_total) as total_montant')
            )
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(12)
            ->get();
    }

    /**
     * Exporter en PDF
     */
    private function exportPDF($view, $data, $filename)
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->download($filename);
    }

    /**
     * Convertir array en CSV
     */
    private function arrayToCsv($data)
    {
        if (empty($data)) return '';
        
        $csv = '';
        $headers = array_keys((array)$data[0]);
        $csv .= implode(';', $headers) . "\n";
        
        foreach ($data as $row) {
            $row = (array)$row;
            foreach ($row as &$value) {
                $value = str_replace([';', "\n", "\r"], [' ', ' ', ' '], $value);
            }
            $csv .= implode(';', $row) . "\n";
        }
        
        return $csv;
    }
}
