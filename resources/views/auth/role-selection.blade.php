<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix du rôle - Tropi-Techno</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-fade-up { animation: fadeInUp 0.6s ease-out; }
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .role-card.selected {
            border: 2px solid #2d6a4f;
            background-color: #f0fdf4;
            transform: scale(1.02);
        }
        .role-radio:checked + .role-card {
            border: 2px solid #2d6a4f;
            background-color: #f0fdf4;
        }
        .role-radio {
            display: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary/20 to-secondary/20 font-inter min-h-screen">
    
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-5xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-up">
            
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-primary to-secondary p-6 text-center">
                <div class="inline-block bg-white/20 p-3 rounded-full mb-3">
                    <i class="fas fa-users text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Accès Administration</h1>
                <p class="text-white/80 mt-1">Sélectionnez votre profil pour continuer</p>
            </div>
            
            <!-- Formulaire de choix -->
            <form id="roleForm" action="" method="GET" class="p-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <!-- Carte Admin -->
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="admin" class="role-radio" data-url="{{ route('admin.login') }}">
                        <div class="role-card bg-white rounded-xl p-6 text-center border-2 border-transparent">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-crown text-primary text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark">Administrateur</h3>
                            <p class="text-sm text-gray-500 mt-2">Gestion complète de la plateforme</p>
                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <i class="fas fa-check-circle text-green-500 text-sm hidden selected-icon"></i>
                                <span class="text-xs text-gray-400">Accès total</span>
                            </div>
                        </div>
                    </label>
                    
                    <!-- Carte Animateur -->
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="animateur" class="role-radio" data-url="{{ route('animateur.login') }}">
                        <div class="role-card bg-white rounded-xl p-6 text-center border-2 border-transparent">
                            <div class="w-20 h-20 bg-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chalkboard-user text-secondary text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark">Animateur</h3>
                            <p class="text-sm text-gray-500 mt-2">Supervision des agents terrain</p>
                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <i class="fas fa-check-circle text-green-500 text-sm hidden selected-icon"></i>
                                <span class="text-xs text-gray-400">Gestion d'équipe</span>
                            </div>
                        </div>
                    </label>
                    
                    <!-- Carte Agent Terrain -->
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="agent" class="role-radio" data-url="{{ route('agent.login') }}">
                        <div class="role-card bg-white rounded-xl p-6 text-center border-2 border-transparent">
                            <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-check text-accent text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark">Agent Terrain</h3>
                            <p class="text-sm text-gray-500 mt-2">Gestion des producteurs sur le terrain</p>
                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <i class="fas fa-check-circle text-green-500 text-sm hidden selected-icon"></i>
                                <span class="text-xs text-gray-400">Travail terrain</span>
                            </div>
                        </div>
                    </label>
                    
                    <!-- Carte Contrôleur -->
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="controleur" class="role-radio" data-url="{{ route('controleur.login') }}">
                        <div class="role-card bg-white rounded-xl p-6 text-center border-2 border-transparent">
                            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clipboard-list text-purple-600 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark">Contrôleur</h3>
                            <p class="text-sm text-gray-500 mt-2">Audit et conformité</p>
                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <i class="fas fa-check-circle text-green-500 text-sm hidden selected-icon"></i>
                                <span class="text-xs text-gray-400">Vérification</span>
                            </div>
                        </div>
                    </label>
                </div>
                
                <!-- Message d'erreur -->
                <div id="errorMessage" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded">
                    Veuillez sélectionner un rôle pour continuer.
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à l'accueil
                    </a>
                    <button type="submit" id="continueBtn" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-secondary transition transform hover:scale-105">
                        Continuer <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
            
            <!-- Footer -->
            <div class="text-center pb-6 text-xs text-gray-400">
                <i class="fas fa-shield-alt mr-1"></i> Accès sécurisé - Choix du profil
            </div>
        </div>
    </div>
    
    <script>
        const radioButtons = document.querySelectorAll('.role-radio');
        const roleCards = document.querySelectorAll('.role-card');
        const form = document.getElementById('roleForm');
        const errorMessage = document.getElementById('errorMessage');
        const continueBtn = document.getElementById('continueBtn');
        
        let selectedRole = null;
        let selectedUrl = null;
        
        // Gestion de la sélection des cartes
        radioButtons.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                // Retirer la classe selected de toutes les cartes
                roleCards.forEach(card => {
                    card.classList.remove('selected');
                });
                
                // Ajouter la classe selected à la carte correspondante
                if (this.checked) {
                    roleCards[index].classList.add('selected');
                    selectedRole = this.value;
                    selectedUrl = this.dataset.url;
                    
                    // Cacher le message d'erreur
                    errorMessage.classList.add('hidden');
                }
            });
            
            // Clic sur la carte pour sélectionner
            roleCards[index].addEventListener('click', () => {
                radio.checked = true;
                const event = new Event('change');
                radio.dispatchEvent(event);
            });
        });
        
        // Soumission du formulaire
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedRole) {
                errorMessage.classList.remove('hidden');
                return;
            }
            
            // Rediriger vers la page de connexion correspondante
            window.location.href = selectedUrl;
        });
    </script>
</body>
</html>