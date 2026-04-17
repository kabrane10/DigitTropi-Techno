<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    /**
     * Afficher la liste des sauvegardes
     */
    public function index()
    {
        $backups = [];
        
        // Vérifier que le dossier existe
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        // Récupérer tous les fichiers .sqlite dans le dossier
        $files = glob(storage_path('app/backups/*.sqlite'));
        
        foreach ($files as $file) {
            $filename = basename($file);
            $backups[] = [
                'name' => $filename,
                'path' => $file,
                'date' => date('d/m/Y H:i:s', filemtime($file)),
                'size' => $this->formatSize(filesize($file))
            ];
        }
        
        // Trier par date décroissante (plus récent en premier)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('admin.backup.index', compact('backups'));
    }
    
    /**
     * Lancer une sauvegarde manuelle
     */
    public function run()
    {
        try {
            // Exécuter la commande de sauvegarde
            $exitCode = Artisan::call('backup:database');
            $output = Artisan::output();
            
            if ($exitCode === 0) {
                return redirect()->route('admin.backup.index')
                    ->with('success', '✅ Sauvegarde effectuée avec succès');
            } else {
                return redirect()->route('admin.backup.index')
                    ->with('error', '❌ Erreur lors de la sauvegarde: ' . $output);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Télécharger une sauvegarde
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/octet-stream',
            ]);
        }
        
        return redirect()->route('admin.backup.index')
            ->with('error', '❌ Fichier non trouvé');
    }
    
    /**
     * Supprimer une sauvegarde
     */
    public function delete($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->route('admin.backup.index')
                ->with('success', '✅ Sauvegarde supprimée avec succès');
        }
        
        return redirect()->route('admin.backup.index')
            ->with('error', '❌ Fichier non trouvé');
    }
    
    /**
     * Formater la taille du fichier
     */
    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}