<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use App\Models\SuiviParcellaire;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $controleur = Auth::guard('controleur')->user();
        
        // Statistiques globales
        $stats = [
            'total_producteurs' => Producteur::count(),
            'producteurs_par_region' => Producteur::select('region', DB::raw('count(*) as total'))
                ->groupBy('region')
                ->get(),
            'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'total_credits' => CreditAgricole::sum('montant_total'),
            'taux_remboursement' => $this->calculerTauxRemboursement(),
            'total_collectes' => Collecte::sum('quantite_nette'),
            'valeur_collectes' => Collecte::sum('montant_total'),
            'collectes_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
            'suivis_mois' => SuiviParcellaire::whereMonth('date_suivi', now()->month)->count(),
            'total_stock' => Stock::sum('stock_actuel'),
            'alertes_stock' => Stock::whereRaw('stock_actuel <= seuil_alerte')->count(),
            'nb_produits_stock' => Stock::distinct('produit')->count('produit'),
        ];
        
        // Calcul des crédits en retard
        $credits_retard = CreditAgricole::where('statut', 'actif')
            ->where('date_echeance', '<', now())
            ->count();
        
        // Derniers producteurs inscrits
        $derniersProducteurs = Producteur::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Top producteurs (collectes)
        $topProducteurs = Producteur::withSum('collectes', 'quantite_nette')
            ->orderBy('collectes_sum_quantite_nette', 'desc')
            ->limit(5)
            ->get();
        
        // Collectes par produit
        $collectesParProduit = Collecte::select('produit', DB::raw('SUM(quantite_nette) as total'))
            ->groupBy('produit')
            ->get();
        
        // Stocks critiques (alertes)
        $stocksCritiques = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();
        
        return view('controleur.dashboard', compact(
            'controleur', 'stats', 'derniersProducteurs', 
            'topProducteurs', 'collectesParProduit', 'stocksCritiques',
            'credits_retard'  // AJOUT DE LA VARIABLE MANQUANTE
        ));
    }
    
    private function calculerTauxRemboursement()
    {
        $total = CreditAgricole::sum('montant_total');
        $rembourse = CreditAgricole::sum('montant_total') - CreditAgricole::sum('montant_restant');
        return $total > 0 ? ($rembourse / $total) * 100 : 0;
    }
}