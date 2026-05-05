## Plan de Sauvegarde de la Base de Données (Google Drive)

### Architecture Technique
Afin de garantir une compatibilité totale avec Laravel 12 et d'éviter les conflits de dépendances liés aux anciens drivers Flysystem, la solution repose sur :
*   **Spatie Laravel Backup :** Pour l'orchestration, la création des dumps SQL et la compression.
*   **Google API Client (`google/apiclient`) :** Utilisation de la bibliothèque officielle Google pour l'envoi des fichiers.
*   **Driver Personnalisé :** Implémentation d'un bridge entre le système de fichiers de Laravel et l'API Google Drive.

### Composants à Implémenter
1.  **GoogleDriveServiceProvider :** Enregistrement d'un driver `google` personnalisé dans le filesystem de Laravel.
2.  **Configuration Filesystem :** Ajout du disque `google` dans `config/filesystems.php` avec les clés d'API (Client ID, Secret, Refresh Token).
3.  **Configuration Backup :** Modification de `config/backup.php` pour désigner le disque `google` comme destination de stockage.
4.  **Automatisation :** Planification des commandes `backup:run` (quotidien) et `backup:clean` (hebdomadaire) dans le scheduler.

### Sécurité et Stockage
*   Les sauvegardes sont chiffrées (si configuré) et envoyées vers un dossier spécifique identifié par son `folderId`.
*   Gestion de la rétention pour limiter l'espace disque utilisé sur le Drive.