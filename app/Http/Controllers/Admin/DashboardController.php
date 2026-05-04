<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Ajout pour l'accès au stockage

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales (CODE EXISTANT INTACT)
        $stats = [
            'total_producteurs' => Producteur::count(),
            'producteurs_actifs' => Producteur::where('statut', 'actif')->count(),
            'superficie_totale' => Producteur::sum('superficie_totale'),
            'total_credits' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'total_collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
            'valeur_collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('montant_total'),
        ];

        // Graphique: Producteurs par région (CODE EXISTANT INTACT)
        $producteurs_par_region = Producteur::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->get();

        // Graphique: Collectes par mois (6 derniers mois) (CODE EXISTANT INTACT)
        $collectes_par_mois = Collecte::select(
                DB::raw('DATE_FORMAT(date_collecte, "%Y-%m") as mois'),
                DB::raw('SUM(quantite_nette) as total')
            )
            ->where('date_collecte', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Top 5 producteurs (CODE EXISTANT INTACT)
        $top_producteurs = Producteur::withSum('collectes', 'quantite_nette')
            ->orderBy('collectes_sum_quantite_nette', 'desc')
            ->limit(5)
            ->get();

        // Alertes stocks (CODE EXISTANT INTACT)
        $stocks_alerte = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();

        // Crédits en retard (CODE EXISTANT INTACT)
        $credits_retard = CreditAgricole::where('statut', 'actif')
            ->where('date_echeance', '<', now())
            ->count();

        // ----- DÉBUT DE LA CORRECTION -----
        // Logique pour trouver la date de la dernière sauvegarde
        $last_backup_date = null;
        try {
            $disk = Storage::disk('local');
            $backup_folder = config('backup.backup.name'); // Nom du dossier de backup
            $files = $disk->files($backup_folder);

            if (!empty($files)) {
                // Trie les fichiers par date de modification pour trouver le plus récent
                $last_backup_file = collect($files)->sortByDesc(function ($file) use ($disk) {
                    return $disk->lastModified($file);
                })->first();

                if($last_backup_file) {
                    $last_backup_date = $disk->lastModified($last_backup_file);
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur (ex: dossier non trouvé), on ne bloque pas la page
            report($e); // Optionnel: log l'erreur
        }
        // ----- FIN DE LA CORRECTION -----


        return view('admin.dashboard', compact(
            'stats', 'producteurs_par_region', 'collectes_par_mois',
            'top_producteurs', 'stocks_alerte', 'credits_retard',
            'last_backup_date' // Ajout de la variable pour la vue
        ));
    }
}
