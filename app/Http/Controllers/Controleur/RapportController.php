<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use Illuminate\Http\Request;  // AJOUTER CETTE LIGNE
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_producteurs' => Producteur::count(),
            'total_credits' => CreditAgricole::sum('montant_total'),
            'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'total_collectes' => Collecte::sum('quantite_nette'),
            'valeur_collectes' => Collecte::sum('montant_total'),
            'taux_remboursement' => $this->calculerTauxRemboursement(),
        ];
        
        $collectesParMois = Collecte::select(
                DB::raw('strftime("%Y-%m", date_collecte) as mois'),
                DB::raw('SUM(quantite_nette) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get();
        
        $creditsParStatut = CreditAgricole::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();
        
        $producteursParRegion = Producteur::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();
        
        $derniersProducteurs = Producteur::orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('controleur.rapports.index', compact('stats', 'collectesParMois', 'creditsParStatut', 'producteursParRegion', 'derniersProducteurs'));
    }

    /**
     * Exporter un rapport en PDF
     */
    public function exportPdf(Request $request)  // Le type hint Request est maintenant reconnu
    {
        try {
            $type = $request->type ?? 'global';
            
            $stats = [
                'total_producteurs' => Producteur::count(),
                'total_credits' => CreditAgricole::sum('montant_total'),
                'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
                'total_collectes' => Collecte::sum('quantite_nette'),
                'valeur_collectes' => Collecte::sum('montant_total'),
                'taux_remboursement' => $this->calculerTauxRemboursement(),
            ];
            
            if ($type == 'producteurs') {
                $data = [
                    'stats' => $stats,
                    'producteurs' => Producteur::orderBy('created_at', 'desc')->limit(50)->get(),
                    'title' => 'Rapport des producteurs'
                ];
                $view = 'controleur.rapports.export-producteurs-pdf';
            } elseif ($type == 'credits') {
                $data = [
                    'stats' => $stats,
                    'credits' => CreditAgricole::with('producteur')->orderBy('created_at', 'desc')->limit(50)->get(),
                    'title' => 'Rapport des crédits'
                ];
                $view = 'controleur.rapports.export-credits-pdf';
            } elseif ($type == 'collectes') {
                $data = [
                    'stats' => $stats,
                    'collectes' => Collecte::with('producteur')->orderBy('date_collecte', 'desc')->limit(50)->get(),
                    'title' => 'Rapport des collectes'
                ];
                $view = 'controleur.rapports.export-collectes-pdf';
            } else {
                $data = [
                    'stats' => $stats,
                    'title' => 'Rapport global d\'activités',
                    'date' => now()->format('d/m/Y H:i')
                ];
                $view = 'controleur.rapports.export-pdf';
            }
            
            $pdf = Pdf::loadView($view, $data);
            return $pdf->download('rapport-' . $type . '-' . date('Y-m-d') . '.pdf');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    private function calculerTauxRemboursement()
    {
        $total = CreditAgricole::sum('montant_total');
        $rembourse = CreditAgricole::sum('montant_total') - CreditAgricole::sum('montant_restant');
        return $total > 0 ? ($rembourse / $total) * 100 : 0;
    }
}