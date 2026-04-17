#!/bin/bash
# Installer Composer s'il n'est pas là
if ! command -v composer &> /dev/null
then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# Installer les dépendances
composer install --no-dev --no-interaction --optimize-autoloader

# Générer le cache seulement si le vendor existe
if [ -f "vendor/autoload.php" ]; then
    php artisan config:cache
    php artisan route:cache
else
    echo "ERREUR : Le dossier vendor n'a pas été créé !"
    exit 1
fi