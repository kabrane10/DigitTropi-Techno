<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Animateur - Tropi-Techno</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        .pulse-slow { animation: pulse 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gradient-to-br from-primary/20 to-secondary/20 font-inter min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full animate-fade-up">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4 pulse-slow">
                <i class="fas fa-chalkboard-user text-primary text-4xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-dark">Tropi-Techno</h1>
            <p class="text-gray-600 mt-1">Espace Animateur</p>
        </div>
        
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Connexion</h2>
                <p class="text-white/80 text-sm">Accédez à votre espace animateur</p>
            </div>
            
            <form action="{{ route('animateur.login.submit') }}" method="POST" class="p-6">
                @csrf
                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm">{{ $errors->first() }}</div>
                @endif
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></div>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition" placeholder="animateur@tropitechno.com">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></div>
                        <input type="password" name="password" id="password" required class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition" placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary"><i id="eyeIcon" class="fas fa-eye-slash"></i></button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded focus:ring-primary"><span class="text-sm text-gray-600">Se souvenir de moi</span></label>
                    <a href="#" class="text-sm text-primary hover:underline">Mot de passe oublié ?</a>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center space-x-2 group">
                    <span>Se connecter</span><i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
                
                <div class="mt-6 pt-4 text-center border-t">
                    <p class="text-xs text-gray-500"><i class="fas fa-shield-alt mr-1 text-green-500"></i>Connexion sécurisée - Accès réservé au personnel autorisé</p>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-6"><p class="text-xs text-gray-500">&copy; {{ date('Y') }} Tropi-Techno Sarl - Agriculture Biologique au Togo</p></div>
    </div>
    
    <script>function togglePassword(){const p=document.getElementById('password'),e=document.getElementById('eyeIcon');if(p.type==='password'){p.type='text';e.classList.remove('fa-eye-slash');e.classList.add('fa-eye');}else{p.type='password';e.classList.remove('fa-eye');e.classList.add('fa-eye-slash');}}</script>
</body>
</html>