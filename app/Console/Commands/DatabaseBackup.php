<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Sauvegarde la base de données SQLite';

    public function handle()
    {
        $databasePath = database_path('database.sqlite');
        
        // Vérifier que la base de données existe
        if (!file_exists($databasePath)) {
            $this->error('❌ Base de données non trouvée: ' . $databasePath);
            return 1;
        }
        
        // Créer le dossier de sauvegarde si nécessaire
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
            $this->info('📁 Dossier de sauvegarde créé: ' . $backupDir);
        }
        
        // Générer le nom du fichier
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = $backupDir . '/' . $filename;
        
        // Copier la base de données
        if (copy($databasePath, $backupPath)) {
            $this->info('✅ Sauvegarde effectuée avec succès: ' . $filename);
            $this->info('📁 Emplacement: ' . $backupPath);
            $this->info('📊 Taille: ' . number_format(filesize($backupPath) / 1024, 2) . ' KB');
            
            // Nettoyer les anciennes sauvegardes (garder 7 jours)
            $this->cleanOldBackups($backupDir);
            
            return 0;
        }
        
        $this->error('❌ Erreur lors de la copie de la base de données');
        return 1;
    }
    
    /**
     * Supprimer les sauvegardes de plus de 7 jours
     */
    private function cleanOldBackups($backupDir)
    {
        $files = glob($backupDir . '/backup-*.sqlite');
        $now = time();
        $deleted = 0;
        
        foreach ($files as $file) {
            // 7 jours = 604800 secondes
            if ($now - filemtime($file) > 604800) {
                unlink($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("🗑️ $deleted ancienne(s) sauvegarde(s) supprimée(s)");
        }
    }
}