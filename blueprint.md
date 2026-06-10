
# Blueprint du Projet

Ce document sert de source unique de vérité pour le développement de l'application. Il documente l'architecture, les fonctionnalités et les décisions de conception.

## 1. Vue d'ensemble

L'application est une plateforme complète pour la gestion des activités d'une coopérative agricole. Elle vise à numériser et à optimiser les processus liés aux producteurs, aux intrants, aux crédits, aux collectes et à la commercialisation des produits. L'objectif est d'améliorer la traçabilité, la transparence et l'efficacité opérationnelle à travers plusieurs interfaces dédiées (Administration, Animateur, Contrôleur, Agent de terrain) et un site public.

---

## 2. Pile Technologique & Dépendances

Cette section détaille les technologies, les frameworks et les paquets qui constituent le socle de l'application.

### 2.1. Backend (PHP)

-   **Framework Principal** : **Laravel 12**
-   **Langage** : PHP 8.2+
-   **Dépendances Serveur Clés (`composer.json`)** :
    -   `barryvdh/laravel-dompdf` : Pour la génération de documents PDF (bordereaux, rapports, etc.).
    -   `maatwebsite/excel` : Pour l'importation et l'exportation de données au format Excel/CSV (listes, rapports).
    -   `spatie/laravel-backup` : Pour la gestion des sauvegardes (fichiers et base de données).
    -   `spatie/laravel-permission` : Pour la gestion fine des rôles et permissions, au cœur du système d'authentification multi-rôles.
    -   `cloudinary-labs/cloudinary-laravel` : Pour la gestion des médias (images, vidéos) sur le service Cloudinary.
    -   `masbug/flysystem-google-drive-ext` : Suggère une intégration avec Google Drive, probablement pour stocker les sauvegardes ou d'autres fichiers.

### 2.2. Frontend (JavaScript & CSS)

-   **Build Tool** : **Vite** est utilisé pour la compilation et le bundling des assets frontend (CSS, JS).
-   **Framework CSS** : **Tailwind CSS** est le framework principal pour la construction de l'interface utilisateur. Il est utilisé avec ses plugins officiels (`@tailwindcss/forms`, `@tailwindcss/typography`, `@tailwindcss/aspect-ratio`).
-   **Framework JavaScript** : **Alpine.js** est utilisé pour apporter de l'interactivité et de la réactivité aux composants de l'interface sans la complexité d'un framework JS plus lourd.
-   **Dépendances Frontend Clés (`package.json`)** :
    -   `axios` : Pour effectuer des requêtes HTTP asynchrones (AJAX) depuis le client.
    -   `chart.js` : Pour la création des graphiques et des diagrammes dans les tableaux de bord et les rapports.
    -   `sweetalert2` : Pour l'affichage de notifications et de boîtes de dialogue modales esthétiques et interactives.
    -   `@fortawesome/fontawesome-free` : Fournit un large éventail d'icônes utilisées dans toute l'application.

---

## 3. Architecture & Modules Fonctionnels

L'application est structurée autour de plusieurs modules clés qui interagissent pour fournir une solution de gestion intégrée.

### 3.1. Site Public & Contenu

Ce module gère l'interface visible par le grand public.

-   **Pages Publiques** : Inclut une page d'accueil, une page de contact, une galerie photo/vidéo et une section d'actualités.
-   **Gestion de Contenu (Admin)** : L'administrateur peut gérer les **Actualités** et la **Galerie** (organisation en **Albums**, téléversement de médias) via une interface dédiée.
-   **API de Données** : Des routes API sont utilisées pour charger dynamiquement le contenu (actualités, photos) sur le site public, améliorant ainsi la performance de chargement.

### 3.2. Authentification & Gestion des Rôles

Le système utilise une authentification multi-rôles pour sécuriser l'accès aux différentes sections de l'application.

-   **Modèles** : `Admin`, `Animateur`, `Controleur`, `AgentTerrain`.
-   **Rôles & Permissions** :
    -   **Administrateur** : Accès complet à toutes les fonctionnalités de gestion.
    -   **Animateur** : Gère les agents de terrain, consulte les données des producteurs et les suivis.
    -   **Agent de Terrain** : Gère les producteurs, les collectes et les suivis parcellaires sur le terrain.
    -   **Contrôleur** : Accès en lecture seule aux données critiques (crédits, stocks, collectes) et peut générer des rapports de supervision.
-   **Portails dédiés** : Chaque rôle possède son propre tableau de bord et ses fonctionnalités spécifiques après connexion.

### 3.3. Gestion du Personnel (Admin)

Ce module centralise la gestion des utilisateurs internes de l'application.

-   **CRUD complet** : L'administrateur peut créer, lire, mettre à jour et supprimer les comptes pour les **Animateurs**, **Contrôleurs** et **Agents de terrain**.
-   **Fonctionnalités Clés** :
    -   Réinitialisation de mot de passe.
    -   Activation/Désactivation de comptes.
    -   Exportation des listes de personnel (format Excel/CSV).

### 3.4. Gestion des Acteurs Externes (Admin)

Ce module est dédié à la gestion des partenaires de la coopérative.

-   **Gestion des Producteurs** : CRUD complet pour les fiches des agriculteurs, incluant leurs informations personnelles et de contact.
-   **Gestion des Coopératives** : CRUD pour les coopératives partenaires, avec un tableau de bord dédié pour suivre leurs opérations spécifiques.

### 3.5. Gestion des Intrants & Semences

Ce module est au cœur de la logistique agricole.

-   **Catalogue d'Intrants & Semences** : CRUD pour définir les types d'intrants (engrais, pesticides) et de semences disponibles.
-   **Gestion de Stock Multi-Zones** :
    -   **Zones de stockage** : Les zones sont gérables dynamiquement par l'administrateur (voir la tâche documentée ci-dessous).
    -   **Suivi des Stocks** : Le système suit la quantité de chaque intrant dans chaque zone.
    -   **Mouvements de Stock** : Enregistrement des entrées (ajouts), sorties (distributions) et transferts de stock entre les zones.
    -   **Alertes de Stock** : Un système de notifications avertit les administrateurs lorsque le stock d'un intrant atteint un seuil critique.
-   **Distributions** :
    -   Enregistrement des distributions de semences et d'intrants aux producteurs ou aux coopératives.
    -   Génération de documents de distribution imprimables.

### 3.6. Opérations Financières & Commerciales

Ce module gère le cycle de vie financier des opérations agricoles.

-   **Estimations de Besoin** : Les producteurs peuvent soumettre des demandes d'intrants, qui peuvent être converties en demandes de crédit.
-   **Gestion des Crédits Agricoles** :
    -   CRUD pour les demandes de crédit.
    -   Suivi des statuts (demandé, approuvé, décaissé, remboursé).
    -   Gestion des **remboursements**.
-   **Gestion des Collectes** : Suivi des quantités de produits agricoles collectées auprès des producteurs.
-   **Gestion des Achats** : Suivi des achats de produits, avec un processus de validation et la génération de bordereaux de paiement.

### 3.7. Logistique & Traçabilité (Bordereaux)

Ce module garantit la traçabilité de toutes les opérations physiques.

-   **Génération de Bordereaux** : Le système permet de générer différents types de bordereaux (documents officiels) pour :
    -   **Chargement** : Pour le transport de marchandises.
    -   **Livraison** : Pour la réception de marchandises.
    -   **Achat Direct** : Pour les transactions au comptant.
    -   **Collecte** : Pour les produits collectés.
    -   **Contre-Passée** : Pour les annulations ou les retours.
-   **Impression & Suivi** : Chaque bordereau est unique, imprimable et son statut (valide, annulé) est suivi.

### 3.8. Suivi Terrain & Parcellaire

Ce module est dédié au suivi des activités directement sur les parcelles des producteurs.

-   **CRUD des Suivis** : Permet aux agents de terrain d'enregistrer des visites, des observations et des recommandations pour chaque parcelle.
-   **Consultation** : Les données de suivi sont consultables par producteur, permettant un historique complet des interventions.

### 3.9. Rapports & Analyse

Un module puissant pour la prise de décision.

-   **Tableau de Bord Central** : Vue d'ensemble des indicateurs de performance clés (KPIs).
-   **Rapports Spécifiques** : Génération de rapports détaillés sur :
    -   Les finances (crédits, achats).
    -   La production (collectes).
    -   L'état des stocks.
    -   Les listes de producteurs et de crédits.
-   **Exportation** : La plupart des rapports peuvent être exportés au format Excel ou PDF.

### 3.10. Utilitaires Système

-   **Notifications en Temps Réel** : Un système de notifications informe les utilisateurs des événements importants (ex: stock bas, nouvelle demande de crédit).
-   **Sauvegardes** : Une interface permet aux administrateurs de lancer, télécharger et supprimer des sauvegardes de la base de données.
-   **Signature Numérique** : Une fonctionnalité avancée permet de signer numériquement des documents. Chaque signature génère un QR code et un lien de vérification unique pour confirmer l'authenticité du document.

---

## 4. Tâches de Fond & Automatisation (Cron Jobs)

Plusieurs tâches critiques sont automatisées et s'exécutent en arrière-plan à des intervalles réguliers pour assurer la maintenance et la proactivité du système. Ces tâches sont définies dans `app/Console/Kernel.php`.

-   **Sauvegarde de la Base de Données (`backup:run`)** :
    -   **Fréquence** : Toutes les 5 minutes (probablement pour le développement ; à revoir pour la production).
    -   **Objectif** : Crée une sauvegarde complète de la base de données pour prévenir la perte de données.

-   **Nettoyage des Anciennes Sauvegardes (`backup:clean`)** :
    -   **Fréquence** : Tous les jours à 3h00.
    -   **Objectif** : Supprime les anciennes sauvegardes pour libérer de l'espace de stockage, en ne conservant que les plus récentes.

-   **Vérification des Alertes de Stock (`stock:check-alertes`)** :
    -   **Fréquence** : Toutes les heures.
    -   **Objectif** : Scanne les niveaux de stock des intrants et génère des notifications pour les administrateurs si un stock est en dessous de son seuil d'alerte.

-   **Génération de Notifications Générales** :
    -   **Fréquence** : Toutes les heures.
    -   **Objectif** : Une tâche planifiée déclenche la génération de notifications diverses (détails à préciser).

-   **Correction du Statut des Crédits (`FixCreditStatus`)** :
    -   **Note** : Bien que la commande existe, elle n'est pas actuellement planifiée pour une exécution automatique. Elle peut être lancée manuellement pour corriger des incohérences de données.

---

## 5. Historique des Tâches

Cette section documente les modifications et les ajouts de fonctionnalités réalisés de manière itérative.

### Tâche : Rendre la Gestion des Zones de Stockage Dynamique

-   **Date** : 24/05/2024
-   **Contexte** : Auparavant, les zones de stockage (`Centrale`, `Kara`, `Savanes`) étaient codées en dur dans le `IntrantController`, ce qui rendait toute modification impossible pour un administrateur.
-   **Solution** : La gestion des zones a été transformée en une fonctionnalité dynamique (CRUD) accessible via l'interface d'administration.
-   **Implémentation** :
    1.  **Base de Données** : Création d'une table `zones` avec un modèle `Zone` et un `ZoneSeeder` pour les données initiales.
    2.  **Logique Métier** : Le `IntrantController` a été modifié pour lire les zones depuis la base de données au lieu d'utiliser un tableau statique.
    3.  **Interface Admin** : Un `ZoneController` (resource) a été créé, ainsi que les vues Blade (`index`, `create`, `edit`) pour permettre la gestion complète des zones. Une logique a été ajoutée pour empêcher la suppression d'une zone si des stocks y sont encore associés.
    4.  **Navigation** : Un lien a été ajouté dans le menu latéral de l'administrateur sous une nouvelle section "Configuration".
-   **Résultat** : Les administrateurs peuvent désormais gérer les zones de stockage de manière autonome, améliorant la flexibilité et la maintenabilité de l'application.

### 3.11. Mode Hors Ligne pour Agents (Offline-First)

Ce module est une fonctionnalité critique conçue pour les agents de terrain travaillant dans des zones à faible ou sans connectivité Internet.

-   **Objectif** : Permettre aux agents de continuer à enregistrer des opérations essentielles (comme les collectes) même sans connexion, afin de ne jamais perdre de données et de ne pas interrompre le travail sur le terrain.

-   **Fonctionnement en 3 étapes** :
    1.  **Mise en Cache de l'Application** : Lorsqu'un agent se connecte pour la première fois avec une connexion active, un **Service Worker** met en cache les éléments essentiels de l'application (pages, styles, scripts). L'application peut alors être ouverte et naviguée même sans connexion Internet.

    2.  **Enregistrement Hors Ligne** : Si un agent soumet un formulaire marqué comme étant "hors ligne compatible" (par exemple, "Enregistrer une collecte") sans connexion, le script `offline.js` intercepte cette action. Au lieu de tenter d'envoyer les données au serveur (ce qui échouerait), il sauvegarde toutes les données du formulaire dans une base de données locale du navigateur, **IndexedDB**.

    3.  **Synchronisation Automatique** : Les données sauvegardées sont placées dans une file d'attente locale. Le Service Worker surveille l'état de la connexion. Dès que le script `offline-js` détecte le retour de la connexion Internet, `offline-sync.js` parcourt la file d'attente stockée avec `localforage` et envoie automatiquement, une par une, les données sauvegardées au serveur pour les enregistrer de manière permanente dans la base de données principale. Une fois les données envoyées avec succès, elles sont supprimées de la file d'attente locale.

-   **Feedback Utilisateur** : L'interface fournie par `offline-js` informe clairement l'agent que ses données ont été sauvegardées localement en mode hors ligne et qu'elles seront synchronisées plus tard. Cela garantit qu'aucune information n'est perdue et donne confiance à l'utilisateur.


