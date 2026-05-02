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
        $driver = DB::connection()->getDriverName();
        $this->info('📁 Driver détecté: ' . $driver);
        
        if ($driver === 'mysql') {
            return $this->backupMySQLPHP();
        }
        
        return $this->backupSQLite();
    }
    
    /**
     * Sauvegarde MySQL en PHP (sans mysqldump)
     */
    private function backupMySQLPHP()
    {
        $filename = 'backup-mysql-' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);
        
        // Créer le dossier si nécessaire
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        try {
            // Récupérer toutes les tables
            $tables = DB::select('SHOW TABLES');
            $sql = "-- --------------------------------------------------------\n";
            $sql .= "-- Sauvegarde Tropi-Techno\n";
            $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- --------------------------------------------------------\n\n";
            
            foreach ($tables as $table) {
                $tableName = reset($table);
                $this->info("📋 Sauvegarde de la table: {$tableName}");
                
                // Structure de la table
                $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "-- Structure de la table `{$tableName}`\n";
                $sql .= "-- --------------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Données de la table
                $rows = DB::table($tableName)->get();
                if (count($rows) > 0) {
                    $sql .= "-- --------------------------------------------------------\n";
                    $sql .= "-- Données de la table `{$tableName}`\n";
                    $sql .= "-- --------------------------------------------------------\n";
                    
                    $batchSize = 100;
                    $rowsArray = $rows->toArray();
                    $chunks = array_chunk($rowsArray, $batchSize);
                    
                    foreach ($chunks as $chunk) {
                        $values = [];
                        foreach ($chunk as $row) {
                            $columns = array_keys((array)$row);
                            $rowValues = [];
                            foreach ($columns as $col) {
                                $value = $row->$col;
                                if (is_null($value)) {
                                    $rowValues[] = 'NULL';
                                } else {
                                    $rowValues[] = "'" . addslashes($value) . "'";
                                }
                            }
                            $values[] = "(" . implode(',', $rowValues) . ")";
                        }
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES \n";
                        $sql .= implode(",\n", $values) . ";\n";
                    }
                    $sql .= "\n";
                }
            }
            
            // Sauvegarde des données
            file_put_contents($backupPath, $sql);
            
            $this->info('✅ Sauvegarde MySQL effectuée: ' . $filename);
            $this->info('📊 Taille: ' . number_format(filesize($backupPath) / 1024, 2) . ' KB');
            
            // Compression
            $this->compressBackup($backupPath);
            
            // Nettoyage
            $this->cleanOldBackups();
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Sauvegarde pour SQLite
     */
    private function backupSQLite()
    {
        $databasePath = database_path('database.sqlite');
        
        if (!file_exists($databasePath)) {
            $this->error('❌ Base de données SQLite non trouvée');
            return 1;
        }
        
        $filename = 'backup-sqlite-' . date('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        if (copy($databasePath, $backupPath)) {
            $this->info('✅ Sauvegarde SQLite: ' . $filename);
            $this->info('📊 Taille: ' . number_format(filesize($backupPath) / 1024, 2) . ' KB');
            
            $this->compressBackup($backupPath);
            $this->cleanOldBackups();
            
            return 0;
        }
        
        $this->error('❌ Erreur lors de la sauvegarde SQLite');
        return 1;
    }
    
    /**
     * Compresser le fichier de sauvegarde
     */
    private function compressBackup($filePath)
    {
        $zipPath = str_replace(['.sql', '.sqlite'], '.zip', $filePath);
        
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFile($filePath, basename($filePath));
            $zip->close();
            
            // Supprimer le fichier original après compression
            unlink($filePath);
            
            $this->info('✅ Compression effectuée: ' . basename($zipPath));
            $this->info('📦 Taille compressée: ' . number_format(filesize($zipPath) / 1024, 2) . ' KB');
            
            return $zipPath;
        }
        
        return null;
    }
    
    /**
     * Nettoyer les anciennes sauvegardes
     */
    private function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $keepDays = 30;
        $deleted = 0;
        
        foreach ($files as $file) {
            $ageInDays = (time() - Storage::lastModified($file)) / 86400;
            if ($ageInDays > $keepDays) {
                Storage::delete($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("🗑️ $deleted ancienne(s) sauvegarde(s) supprimée(s)");
        }
    }
}