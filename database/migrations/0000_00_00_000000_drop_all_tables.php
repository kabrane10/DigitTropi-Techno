<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Nuclear reset migration — runs first (timestamp 0000_00_00_000000).
 *
 * Bypasses Laravel's schema builder entirely and issues raw SQL so that
 * foreign-key constraints cannot block the drops.  After this migration
 * completes the database is completely empty, allowing every subsequent
 * migration to run against a clean slate.
 *
 * This is intentionally a one-way operation: the down() method is a no-op
 * because there is nothing meaningful to restore.
 */
return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            // Leaf tables first (no outgoing FK references to other app tables)
            'remboursements',
            'achats',
            'bordereaux',
            'notifications',
            'suivis_parcellaires',
            'distributions_semences',
            'collectes',
            'credits_agricoles',
            'producteurs',
            'agents_terrain',
            'animateurs',
            'controleurs',
            'stocks',
            'semences',
            'cooperatives',
            'admins',
            'roles',
            'permissions',
            'contact_messages',
            'actualites',
            'galeries',
            // Laravel framework tables
            'failed_jobs',
            'job_batches',
            'jobs',
            'cache_locks',
            'cache',
            'password_reset_tokens',
            'sessions',
            'users',
            'migrations',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS `{$table}`");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Intentional no-op — this migration only runs to clear stale tables
        // before a fresh migration run; there is nothing to reverse.
    }
};
