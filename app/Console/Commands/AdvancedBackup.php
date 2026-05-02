<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvancedBackup extends Command
{
    protected $signature = 'backup:advanced';
    protected $description = 'Sauvegarde de la base de données';

    public function handle()
    {
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le dossier
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        try {
            // Récupérer toutes les tables
            $tables = DB::select('SHOW TABLES');
            $sql = '';
            
            foreach ($tables as $table) {
                $tableName = reset($table);
                
                // Structure de la table
                $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                $sql .= "-- Structure de la table {$tableName}\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Données de la table
                $rows = DB::table($tableName)->get();
                if (count($rows) > 0) {
                    $sql .= "-- Données de la table {$tableName}\n";
                    foreach ($rows as $row) {
                        $columns = array_keys((array)$row);
                        $values = array_map(function($value) {
                            return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                        }, (array)$row);
                        $sql .= "INSERT INTO {$tableName} (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            // Écrire le fichier
            file_put_contents($backupPath, $sql);
            
            $this->info('✅ Sauvegarde effectuée: ' . $filename);
            $this->info('📊 Taille: ' . number_format(filesize($backupPath) / 1024, 2) . ' KB');
            
            // Nettoyer les anciennes sauvegardes
            $this->cleanOldBackups();
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $keepDays = 30;
        $deleted = 0;
        
        foreach ($files as $file) {
            if (Storage::lastModified($file) < now()->subDays($keepDays)->timestamp) {
                Storage::delete($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("🗑️ $deleted ancienne(s) sauvegarde(s) supprimée(s)");
        }
    }
}