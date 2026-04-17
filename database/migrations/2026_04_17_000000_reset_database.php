<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Reset migration — drops all application tables so that subsequent
 * migrations can recreate them cleanly without "table already exists" errors.
 *
 * Tables are dropped in reverse foreign-key dependency order so that
 * MySQL does not raise constraint violations even when FK checks are on.
 * A blanket SET FOREIGN_KEY_CHECKS=0 / SET FOREIGN_KEY_CHECKS=1 guard is
 * also used for safety.
 */
return new class extends Migration
{
    /**
     * All application tables in safe drop order (children before parents).
     */
    private array $tables = [
        // Leaf / child tables first
        'notifications',
        'remboursements',
        'achats',
        'bordereaux',
        'suivis_parcellaires',
        'collectes',
        'distributions_semences',
        'credits_agricoles',
        'agents_terrain',
        'controleurs',
        'admins',
        'contact_messages',
        'galeries',
        'actualites',
        'stocks',
        'animateurs',
        'semences',
        'producteurs',
        'cooperatives',
        'roles',
        'permissions',
        // Laravel framework tables
        'jobs',
        'job_batches',
        'failed_jobs',
        'cache',
        'cache_locks',
        'sessions',
        'password_reset_tokens',
        'users',
        'migrations',
    ];

    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Intentionally empty — this migration only drops tables.
        // Re-running the full migration stack will recreate everything.
    }
};
