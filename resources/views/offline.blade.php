<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hors ligne - Tropi-Techno</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-primary/20 to-secondary/20 font-inter min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 text-center">
        <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-wifi-slash text-orange-500 text-4xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Vous êtes hors ligne</h1>
        <p class="text-gray-600 mb-6">
            Certaines fonctionnalités sont disponibles hors ligne.<br>
            Vous pouvez continuer à travailler, les données seront synchronisées automatiquement.
        </p>
        <div class="space-y-3">
            <a href="{{ route('agent.producteurs.create') }}" class="block w-full bg-primary text-white py-3 rounded-lg font-semibold">
                 Ajouter un producteur
            </a>
            <a href="{{ route('agent.collectes.create') }}" class="block w-full bg-primary text-white py-3 rounded-lg font-semibold">
                 Ajouter une collecte
            </a>
            <a href="{{ route('agent.suivi.create') }}" class="block w-full bg-primary text-white py-3 rounded-lg font-semibold">
                 Ajouter un suivi
            </a>
        </div>
        <div class="mt-6 pt-6 border-t">
            <button onclick="window.location.reload()" class="text-primary hover:underline">
                <i class="fas fa-sync-alt mr-1"></i>Vérifier la connexion
            </button>
        </div>
    </div>
</body>
</html>