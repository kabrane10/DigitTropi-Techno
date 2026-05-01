<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseCompatibility
{
    /**
     * Obtenir la fonction de formatage de date selon le driver
     */
    protected function getDateFormatFunction($column, $format = '%Y-%m')
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            $formatMap = [
                '%Y-%m' => '%Y-%m',
                '%Y' => '%Y',
                '%m' => '%m',
                '%d' => '%d',
            ];
            $mysqlFormat = $formatMap[$format] ?? '%Y-%m';
            return "DATE_FORMAT({$column}, '{$mysqlFormat}')";
        }
        
        // SQLite
        return "strftime('{$format}', {$column})";
    }
    
    /**
     * Vérifier si on est en production MySQL
     */
    protected function isMySQL()
    {
        return DB::connection()->getDriverName() === 'mysql';
    }
}