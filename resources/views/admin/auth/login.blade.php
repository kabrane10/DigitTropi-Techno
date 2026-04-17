<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Administration Tropi-Techno</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .bg-pattern {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%232d6a4f" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
        }
        .input-group:focus-within {
            transform: translateY(-2px);
        }
        .input-group input:focus {
            box-shadow: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary/20 to-secondary/20 font-inter min-h-screen flex items-center justify-center p-4">
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-secondary/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="max-w-md w-full animate-fade-up">
        <!-- Logo et titre -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white/20 backdrop-blur-sm p-4 rounded-2xl mb-4 animate-float">
                <!-- <i class="fas fa-leaf text-primary text-4xl"></i> -->
                <img src="{{ asset('images/img6.png') }}" class="h-16 w-auto rounded-full">
            </div>
            <h1 class="text-3xl font-bold text-dark">Tropi-Techno</h1>
            <p class="text-gray-600 mt-2">Espace Administration</p>
        </div>
        
        <!-- Carte de connexion -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-secondary p-6 text-center">
                <h2 class="text-2xl font-bold text-white">Connexion</h2>
                <p class="text-white/80 text-sm mt-1">Accédez à votre espace d'administration</p>
            </div>
            
            <form action="{{ route('admin.login.submit') }}" method="POST" class="p-8">
                @csrf
                
                <!-- Champ Email -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-primary mr-2"></i>Adresse email
                    </label>
                    <div class="input-group relative transition-all duration-300">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="admin@tropitechno.com"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                    @enderror
                </div>
                
                <!-- Champ Mot de passe avec œil -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-primary mr-2"></i>Mot de passe
                    </label>
                    <div class="input-group relative transition-all duration-300">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" required 
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="••••••••">
                        <button type="button" id="togglePassword" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                    @enderror
                </div>
                
                <!-- Options supplémentaires -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded focus:ring-primary">
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    <a href="#" class="text-sm text-primary hover:underline">Mot de passe oublié ?</a>
                </div>
                
                <!-- Bouton de connexion -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-primary to-secondary text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center space-x-2 group">
                    <span>Se connecter</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
                
                <!-- Informations supplémentaires -->
                <div class="mt-6 pt-4 text-center border-t">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-shield-alt mr-1 text-green-500"></i>
                        Connexion sécurisée - Accès réservé au personnel autorisé
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Tropi-Techno Sarl - Tous droits réservés
            </p>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                // Toggle le type d'input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle l'icône
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }
        
        // Animation sur les champs
        const inputs = document.querySelectorAll('.input-group input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });
    </script>
</body>
</html>