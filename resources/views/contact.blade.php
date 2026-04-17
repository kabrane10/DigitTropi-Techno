<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact - Tropi-Techno Sarl | Agriculture Biologique au Togo</title>
    <meta name="description" content="Contactez Tropi-Techno Sarl pour vos besoins en agriculture biologique, semences certifiées et formations agricoles au Togo.">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <!-- Vite - Si ça ne marche pas, commentez et utilisez l'alternative -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback CSS si Vite ne marche pas -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom, #f9fafb, #ffffff);
        }
        
        /* Classes d'animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .ml-13 {
            margin-left: 3.25rem;
        }
        
        .hero-pattern {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%232d6a4f" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
        }
        
        .scale-105 {
            transform: scale(1.05);
        }
        
        .btn-primary-custom {
            background: linear-gradient(to right, #2d6a4f, #52b788);
        }
        
        .btn-primary-custom:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(45, 106, 79, 0.3);
        }
    </style>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Tailwind CSS manuel pour les classes de base */
        .container { max-width: 1280px; margin-left: auto; margin-right: auto; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-16 { padding-top: 4rem; padding-bottom: 4rem; }
        .py-20 { padding-top: 5rem; padding-bottom: 5rem; }
        .pt-32 { padding-top: 8rem; }
        .pb-20 { padding-bottom: 5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        }
    </style>
</head>
<body>

<!-- Inclusion de la navbar -->
@include('partials.navbar')

<!-- Hero Section -->
<section class="relative pt-32 pb-20 hero-pattern overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-primary/10 px-4 py-2 rounded-full mb-6">
                <i class="fas fa-headset text-primary text-sm"></i>
                <span class="text-primary text-sm font-medium">Support 24/7</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-dark mb-6">
                Contactez notre <span class="text-primary">équipe</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Nous sommes là pour répondre à toutes vos questions et vous accompagner dans vos projets agricoles
            </p>
        </div>
    </div>
</section>

<!-- Messages Flash -->
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 mt-6">
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

@if($errors->any())
<div class="max-w-7xl mx-auto px-4 mt-6">
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
        <i class="fas fa-exclamation-circle mr-2"></i>
        Veuillez corriger les erreurs du formulaire.
    </div>
</div>
@endif

<!-- Section Contact Principal -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Informations de contact - Carte de gauche -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact direct -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-phone-alt text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark ml-3">Contact direct</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-phone text-green-600 text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Téléphone fixe</p>
                                <a href="tel:+22825506312" class="text-gray-700 hover:text-primary font-medium">+228 25 50 63 12</a>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fab fa-whatsapp text-blue-600 text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">WhatsApp / TGC</p>
                                <a href="https://wa.me/22892952961" class="text-gray-700 hover:text-primary font-medium">+228 92 95 29 61</a>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-purple-600 text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Mobile</p>
                                <a href="tel:+22898988013" class="text-gray-700 hover:text-primary font-medium">+228 98 98 80 13</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email & Web -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-secondary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark ml-3">Email & Web</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-6"></i>
                            <a href="mailto:tropitechno@admin.com" class="text-gray-700 hover:text-primary ml-3">tropitechno@admin.com</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-globe text-gray-400 w-6"></i>
                            <a href="https://www.tropitechno.online" class="text-gray-700 hover:text-primary ml-3">www.tropitechno.online</a>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-accent text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark ml-3">Notre siège</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-location-dot text-primary mt-1 w-5"></i>
                            <div class="ml-3">
                                <p class="text-gray-700">RN:17, Bamabodolo</p>
                                <p class="text-gray-700">Sokodé - Togo</p>
                                <p class="text-gray-600 text-sm mt-2">BP: 584 Sokodé-Togo</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Horaires d'ouverture</p>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Lundi - Vendredi:</span>
                                <span>08:00 - 17:00</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mt-1">
                                <span>Samedi:</span>
                                <span>09:00 - 13:00</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mt-1">
                                <span>Dimanche:</span>
                                <span>Fermé</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations légales -->
                <div class="bg-gradient-to-r from-primary/5 to-secondary/5 rounded-2xl p-6 border border-primary/10">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-file-contract text-primary text-xl"></i>
                        <h3 class="text-lg font-semibold text-dark ml-3">Informations légales</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">RCCM:</span>
                            <span class="text-gray-800 font-medium">TG-LOM 2018 B 2959</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">NIF:</span>
                            <span class="text-gray-800 font-medium">1001371023</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">CNSS:</span>
                            <span class="text-gray-800 font-medium">82874</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-primary to-secondary px-8 py-6">
                        <h3 class="text-2xl font-bold text-white">Envoyez-nous un message</h3>
                        <p class="text-white/80 mt-1">Nous vous répondrons dans les 24 heures</p>
                    </div>
                    
                    <form action="{{ url('/contact') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-user text-primary mr-2"></i>Nom complet *
                                </label>
                                <input type="text" name="nom" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                       placeholder="Votre nom et prénom">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-envelope text-primary mr-2"></i>Email *
                                </label>
                                <input type="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                       placeholder="votre@email.com">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-phone text-primary mr-2"></i>Téléphone *
                                </label>
                                <input type="tel" name="telephone" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                       placeholder="+228 XX XX XX XX">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-tag text-primary mr-2"></i>Sujet *
                                </label>
                                <select name="sujet" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Sélectionnez un sujet</option>
                                    <option value="Information sur semences">Information sur semences</option>
                                    <option value="Demande de crédit agricole">Demande de crédit agricole</option>
                                    <option value="Formation agricole">Formation agricole</option>
                                    <option value="Partenariat">Partenariat</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-comment text-primary mr-2"></i>Message *
                            </label>
                            <textarea name="message" required rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                      placeholder="Décrivez votre demande en détail..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-primary to-secondary text-white py-4 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 flex items-center justify-center space-x-2">
                            <span>Envoyer le message</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center mt-4">
                            <i class="fas fa-lock mr-1"></i> Vos informations sont confidentielles
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Carte Google Maps -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.123456789012!2d1.123456!3d8.987654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwNTknMTUuNiJOIDHCsDA3JzI0LjUiRQ!5e0!3m2!1sfr!2stg!4v1234567890123" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-dark">Questions fréquentes</h2>
            <p class="text-gray-600 mt-4">Trouvez rapidement une réponse à vos questions</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-seedling text-primary"></i>
                    </div>
                    <h3 class="font-semibold text-dark ml-3">Quels types de semences proposez-vous ?</h3>
                </div>
                <p class="text-gray-600 text-sm">Nous proposons des semences certifiées bio-locales : soja, arachide, sésame, maïs, riz, gombo et plus encore.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-secondary/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-secondary"></i>
                    </div>
                    <h3 class="font-semibold text-dark ml-3">Comment obtenir un crédit agricole ?</h3>
                </div>
                <p class="text-gray-600 text-sm">Contactez notre équipe via le formulaire ou par téléphone pour étudier votre dossier.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-accent/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard-user text-accent"></i>
                    </div>
                    <h3 class="font-semibold text-dark ml-3">Proposez-vous des formations ?</h3>
                </div>
                <p class="text-gray-600 text-sm">Oui, nous organisons régulièrement des formations en agriculture biologique et bonnes pratiques agricoles.</p>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-primary"></i>
                    </div>
                    <h3 class="font-semibold text-dark ml-3">Livrez-vous dans toute la région ?</h3>
                </div>
                <p class="text-gray-600 text-sm">Nous couvrons les régions Centrale, Kara et Savanes avec notre réseau de distribution.</p>
            </div>
        </div>
    </div>
</section>
@include ("partials.footer")
</body>
</html>