<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration WhatsApp
    |--------------------------------------------------------------------------
    */
    
    // Numéros de contact
    'numbers' => [
        'general' => '22892952961',      // Directeur Général
        'support' => '22825506312',       // Support technique
        'commercial' => '22898988013',    // Service commercial
        'emergency' => '22892952961',     // Urgence
    ],
    
    // Messages par défaut
    'default_messages' => [
        'general' => 'Bonjour, je souhaite obtenir plus d\'informations sur vos services.',
        'support' => 'Bonjour, j\'ai besoin d\'assistance technique sur la plateforme.',
        'commercial' => 'Bonjour, je souhaite recevoir un devis pour vos produits.',
        'partnership' => 'Bonjour, je suis intéressé par un partenariat avec Tropi-Techno.',
    ],
    
    // Horaires de disponibilité
    'availability' => [
        'monday_friday' => '08:00 - 17:00',
        'saturday' => '09:00 - 13:00',
        'sunday' => 'Fermé',
    ],
];