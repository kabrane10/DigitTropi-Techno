<?php

return [
    'backup' => [
        'name' => 'tropi-techno-backup',
        
        'source' => [
            'files' => [
                'include' => [
                    base_path(),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    storage_path('logs'),
                    storage_path('framework'),
                ],

                'follow_links' => false,
                'ignore_unreadable_directories' => false,
                'relative_path' => null,
            ],
            
            'databases' => [
                'mysql',
            ],
        ],
        
        'destination' => [
            'filename_prefix' => '',
            'disks' => [
                'local',
                'google',
            ],
        ],
    ],
    
    'notifications' => [
        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailed::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailed::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessful::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFound::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessful::class => ['mail'],
        ],
        
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,
        
        'mail' => [
            'to' => 'kabranehenry@gmail.com',
        ],
    ],
    
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'tropi-techno-backup'),
            'disks' => ['local', 'google'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
    ],
    
    'cleanup' => [
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,
        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 4,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],

     /*
    |--------------------------------------------------------------------------
    | Configuration des sauvegardes
    |--------------------------------------------------------------------------
    */
    
    // Jours de conservation
    'keep_days' => env('BACKUP_KEEP_DAYS', 30),
    
    // Destinations
    'destinations' => [
        'local' => [
            'enabled' => true,
            'path' => storage_path('app/backups'),
        ],
        'email' => [
            'enabled' => env('BACKUP_EMAIL_ENABLED', false),
            'recipient' => env('BACKUP_EMAIL', 'kabranehenry@gmail.com'),
        ],
        'ftp' => [
            'enabled' => env('BACKUP_FTP_ENABLED', false),
            'host' => env('BACKUP_FTP_HOST'),
            'username' => env('BACKUP_FTP_USERNAME'),
            'password' => env('BACKUP_FTP_PASSWORD'),
            'path' => env('BACKUP_FTP_PATH', '/backups'),
        ],
        'dropbox' => [
            'enabled' => env('BACKUP_DROPBOX_ENABLED', false),
            'token' => env('BACKUP_DROPBOX_TOKEN'),
        ],
    ],
    
    // Compression
    'compression' => [
        'enabled' => true,
        'format' => 'zip', // zip, gz
    ],
    
    // Chiffrement
    'encryption' => [
        'enabled' => env('BACKUP_ENCRYPTION_ENABLED', false),
        'key' => env('BACKUP_ENCRYPTION_KEY'),
    ],
    
    // Notifications
    'notifications' => [
        'enabled' => true,
        'email' => env('BACKUP_NOTIFICATION_EMAIL', 'admin@tropitechno.com'),
        'slack_webhook' => env('BACKUP_SLACK_WEBHOOK'),
    ],
];