<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Collecte;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // ... (votre code existant)

        // ----- DÉBUT DE LA CORRECTION ROBUSTE -----
        $last_backup_date = null; // Initialisation par défaut
        try {
            $disk = Storage::disk('local');
            $backup_folder = config('backup.backup.name');
            
            // Vérifie si le dossier existe pour éviter les erreurs
            if ($disk->exists($backup_folder)) {
                $files = $disk->files($backup_folder);

                if (!empty($files)) {
                    $last_backup_file = collect($files)->sortByDesc(function ($file) use ($disk) {
                        return $disk->lastModified($file);
                    })->first();

                    if($last_backup_file) {
                        $last_backup_date = $disk->lastModified($last_backup_file);
                    }
                }
            }
        } catch (\Exception $e) {
            report($e); // Log l'erreur pour le débogage, mais ne bloque pas la page
        }
        // ----- FIN DE LA CORRECTION ROBUSTE -----

        return view('admin.dashboard', compact(
            'stats', 'producteurs_par_region', 'collectes_par_mois',
            'top_producteurs', 'stocks_alerte', 'credits_retard',
            'last_backup_date' // La variable est TOUJOURS définie, même si elle est null
        ));
    }
}
