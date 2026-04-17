<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_producteurs' => Producteur::count(),
            'producteurs_actifs' => Producteur::where('statut', 'actif')->count(),
            'superficie_totale' => Producteur::sum('superficie_totale'),
            'total_credits' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'total_collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
            'valeur_collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('montant_total'),
        ];

        // Graphique: Producteurs par région
        $producteurs_par_region = Producteur::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();

        // Graphique: Collectes par mois (6 derniers mois)
        $collectes_par_mois = Collecte::select(
                DB::raw('DATE_FORMAT(date_collecte, "%Y-%m") as mois'),
                DB::raw('SUM(quantite_nette) as total')
            )
            ->where('date_collecte', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Top 5 producteurs
        $top_producteurs = Producteur::withSum('collectes', 'quantite_nette')
            ->orderBy('collectes_sum_quantite_nette', 'desc')
            ->limit(5)
            ->get();

        // Alertes stocks
        $stocks_alerte = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();

        // Crédits en retard
        $credits_retard = CreditAgricole::where('statut', 'actif')
            ->where('date_echeance', '<', now())
            ->count();

        return view('admin.dashboard', compact(
            'stats', 'producteurs_par_region', 'collectes_par_mois',
            'top_producteurs', 'stocks_alerte', 'credits_retard'
        ));
    }
}