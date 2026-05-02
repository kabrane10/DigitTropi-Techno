@extends('layouts.app')

@section('content')
<!-- Hero Section avec Carrousel -->
<section id="home" class="relative min-h-screen overflow-hidden">
    
    <!-- Carrousel -->
    <div class="relative h-screen">
        <!-- Slides -->
        <div id="heroSlides" class="relative w-full h-full">
            
            <!-- Slide 1 - Agriculture & Innovation -->
            <div class="slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-100" data-slide="0">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/img2.png') }}');">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/55 to-secondary/55"></div>
                </div>
                <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4">
                    <div class="max-w-4xl mx-auto animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                            <i class="fas fa-leaf text-accent text-sm"></i>
                            <span class="text-sm font-medium">Agriculture Biologique au Togo</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-6 leading-tight">
                            L'art d'optimiser la<br>productivité agricole
                        </h1>
                        <p class="text-xl md:text-2xl mb-8 text-white/90">
                            dans l'<span class="font-semibold text-accent">intégrité biologique</span>!
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="#services" class="bg-accent text-dark px-8 py-3 rounded-full font-semibold hover:bg-white transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-search mr-2"></i>Découvrir nos services
                            </a>
                            <a href="https://wa.me/22825506312" class="border-2 border-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-primary transition-all duration-300">
                                <i class="fas fa-headset mr-2"></i>Nous contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 - Semences Bio-locales -->
            <div class="slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0" data-slide="1">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/img4.png') }}');">
                    <div class="absolute inset-0 bg-gradient-to-r from-dark/55 to-primary/55"></div>
                </div>
                <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4 pt-20">
                    <div class="max-w-4xl mx-auto animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                            <i class="fas fa-seedling text-accent text-sm"></i>
                            <span class="text-sm font-medium">Semences Certifiées</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-6 leading-tight">
                            Des semences <span class="text-accent">bio-locales</span><br>pour une agriculture durable
                        </h1>
                        <p class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto">
                            Production de semences certifiées bio : soja, arachide, sésame, maïs, riz. Adaptées aux réalités climatiques locales.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-check-circle text-accent"></i>
                                <span class="text-sm">Certification rigoureuse</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-chart-line text-accent"></i>
                                <span class="text-sm">Haute performance</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-leaf text-accent"></i>
                                <span class="text-sm">100% Bio local</span>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="#services" class="inline-flex items-center space-x-2 bg-accent text-dark px-6 py-2 rounded-full font-semibold hover:bg-white transition">
                                <span>Découvrir nos semences</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 - Formation & Innovation -->
            <div class="slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0" data-slide="2">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/img5.png') }}');">
                    <div class="absolute inset-0 bg-gradient-to-r from-secondary/75 to-primary/75"></div>
                </div>
                <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4 pt-20">
                    <div class="max-w-4xl mx-auto animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                            <i class="fas fa-graduation-cap text-accent text-sm"></i>
                            <span class="text-sm font-medium">Formation & Certification</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-6  leading-tight">
                            Accompagnement expert<br>pour votre <span class="text-accent">certification bio</span>
                        </h1>
                        <p class="text-xl md:text-2xl mb-8 text-white/90 max-w-3xl mx-auto">
                            Formations pratiques sur le terrain, certifications CE/EOS, NOP et SPG. Un suivi technique continu pour votre réussite.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-chalkboard-user text-accent"></i>
                                <span class="text-sm">Formation terrain</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-certificate text-accent"></i>
                                <span class="text-sm">Certification internationale</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                                <i class="fas fa-headset text-accent"></i>
                                <span class="text-sm">Suivi personnalisé</span>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('contact') }}" class="inline-flex items-center space-x-2 bg-accent text-dark px-6 py-2 rounded-full font-semibold hover:bg-white transition">
                                <span>Nous contacter</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Indicateurs (dots) -->
        <div class="absolute bottom-8 left-0 right-0 z-20 flex justify-center space-x-3">
            <button class="dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300 active" data-dot="0"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300" data-dot="1"></button>
            <button class="dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300" data-dot="2"></button>
        </div>
        
        <!-- Flèches navigation -->
        <button id="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 backdrop-blur-sm">
            <i class="fas fa-chevron-left text-xl"></i>
        </button>
        <button id="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/20 hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 backdrop-blur-sm">
            <i class="fas fa-chevron-right text-xl"></i>
        </button>
    </div>
    
    <!-- Statistiques flottantes -->
    <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/95 backdrop-blur-sm py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="animate-slide-up">
                    <div class="text-2xl md:text-3xl font-bold text-primary">10+</div>
                    <div class="text-sm text-gray-600">Années d'expertise</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.1s">
                    <div class="text-2xl md:text-3xl font-bold text-primary">500+</div>
                    <div class="text-sm text-gray-600">Agriculteurs formés</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.2s">
                    <div class="text-2xl md:text-3xl font-bold text-primary">50+</div>
                    <div class="text-sm text-gray-600">Variétés de semences</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.3s">
                    <div class="text-2xl md:text-3xl font-bold text-primary">100%</div>
                    <div class="text-sm text-gray-600">Bio et local</div>
                </div>
            </div>
        </div>
    </div>

 <!-- --------------------------------------------------------
 /------------------- Section Notre Mission-------------------/
  ----------------------------------------------------------- -->
<section id="mission" class="py-24 bg-gradient-to-br from-white to-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center mb-4">
                <div class="w-12 h-px bg-primary"></div>
                <div class="mx-3 p-2 bg-primary/10 rounded-full">
                    <i class="fas fa-bullseye text-primary text-xl"></i>
                </div>
                <div class="w-12 h-px bg-primary"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-dark mb-4">
                Notre <span class="text-primary">Mission</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Promouvoir la productivité agricole des exploitations biologiques
            </p>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full mt-6"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Colonne gauche - Texte -->
            <div class="space-y-8 animate-fade-in-up">
                
                <!-- Notre Approche Gagnante -->
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-trophy text-primary text-lg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-dark">Notre Approche Gagnante</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-primary mt-1 flex-shrink-0"></i>
                            <span>Organisation des agriculteurs-multiplicateurs ruraux en unité de production des semences préservant une intégrité biologique et adaptées aux réalités climatiques.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-primary mt-1 flex-shrink-0"></i>
                            <span>La garantie bio-locale est assurée par un processus participatif basé sur un cahier de charge communautaire qui implique une diversité d'acteurs des filières biologiques.</span>
                        </li>
                    </ul>
                </div>

                <!-- 3 Cards colorées -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Carte 1 - Intégrité Biologique -->
                    <div class="group relative overflow-hidden rounded-xl p-5 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                         style="background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-leaf text-white text-2xl"></i>
                            </div>
                            <h4 class="text-white font-bold text-base mb-2">Intégrité Biologique</h4>
                            <p class="text-white/80 text-xs leading-relaxed">
                                Préservation de l'intégrité biologique des semences adaptées aux réalités climatiques locales.
                            </p>
                        </div>
                    </div>

                    <!-- Carte 2 - Approche Participative -->
                    <div class="group relative overflow-hidden rounded-xl p-5 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                         style="background: linear-gradient(135deg, #52b788 0%, #74c69d 100%);">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-handshake text-white text-2xl"></i>
                            </div>
                            <h4 class="text-white font-bold text-base mb-2">Approche Participative</h4>
                            <p class="text-white/80 text-xs leading-relaxed">
                                Processus participatif impliquant une diversité d'acteurs des filières biologiques.
                            </p>
                        </div>
                    </div>

                    <!-- Carte 3 - Innovation Technique -->
                    <div class="group relative overflow-hidden rounded-xl p-5 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-xl"
                         style="background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%);">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-microchip text-white text-2xl"></i>
                            </div>
                            <h4 class="text-white font-bold text-base mb-2">Innovation Technique</h4>
                            <p class="text-white/80 text-xs leading-relaxed">
                                Technologies et équipements agricoles adaptés aux besoins des exploitations biologiques.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Photo -->
            <div class="relative animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="relative group">
                    <!-- Effet de cadre décoratif -->
                    <div class="absolute -top-4 -left-4 w-32 h-32 border-t-4 border-l-4 border-primary/30 rounded-tl-2xl"></div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 border-b-4 border-r-4 border-primary/30 rounded-br-2xl"></div>
                    
                    <!-- Image principale -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/50 to-transparent z-10"></div>
                        <img src="{{ asset('images/img6.png') }}" 
                             class="w-full h-[500px] object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Badge flottant -->
                        <div class="absolute bottom-6 left-6 z-20 bg-white/90 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                                    <i class="fas fa-seedling text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Depuis 2018</p>
                                    <p class="font-bold text-dark">Agriculture Bio</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cercle flottant décoratif -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-secondary/10 rounded-full blur-3xl"></div>
                    </div>
                </div>

                <!-- Statistiques flottantes -->
                <div class="grid grid-cols-3 gap-3 mt-6">
                    <div class="text-center p-3 bg-white rounded-xl shadow-md">
                        <div class="text-xl font-bold text-primary">10+</div>
                        <div class="text-xs text-gray-500">Années</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-xl shadow-md">
                        <div class="text-xl font-bold text-primary">500+</div>
                        <div class="text-xs text-gray-500">Agriculteurs</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-xl shadow-md">
                        <div class="text-xl font-bold text-primary">100%</div>
                        <div class="text-xs text-gray-500">Bio local</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--------------------------------------------------------
                          Section Nos Services 
----------------------------------------------------------->
<section id="services" class="py-24 bg-gradient-to-br from-white to-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center mb-4">
                <div class="w-12 h-px bg-primary"></div>
                <div class="mx-3 p-2 bg-primary/10 rounded-full">
                    <i class="fas fa-cogs text-primary text-xl"></i>
                </div>
                <div class="w-12 h-px bg-primary"></div>
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-dark mb-4">
                Nos Services <span class="text-primary">d'Excellence</span>
            </h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
                Une gamme complète de services pour révolutionner votre productivité agricole biologique
            </p>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full mt-6"></div>
        </div>

        <!-- Cartes services -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Carte 1 - Multiplication & Conditionnement -->
            <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <!-- Dégradé d'arrière-plan au hover -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <!-- En-tête coloré -->
                <div class="relative bg-gradient-to-r from-primary to-secondary p-6 text-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-seedling text-white text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-white/30">01</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Multiplication & <br>Conditionnement</h3>
                </div>
                
                <!-- Contenu -->
                <div class="relative p-6">
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Des semences certifiées bio-locales pour une agriculture durable et productive.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-primary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Production de semences certifiées bio (soja, arachide, sésame, riz, maïs)</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-primary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Adaptation aux conditions climatiques locales</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-primary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Processus de certification rigoureux</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-primary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Traçabilité complète des semences</span>
                        </li>
                    </ul>
                    
                    <!-- Badge -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center text-xs text-primary">
                            <i class="fas fa-star mr-1"></i> Certifié bio-local
                        </span>
                    </div>
                </div>
            </div>

            <!-- Carte 2 - Promotion des Intrants -->
            <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-accent/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative bg-gradient-to-r from-secondary to-accent p-6 text-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tractor text-white text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-white/30">02</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Promotion des <br>Intrants</h3>
                </div>
                
                <div class="relative p-6">
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Technologies et équipements agricoles innovants pour optimiser vos rendements.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-secondary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Intrants 100% biologiques et organiques</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-secondary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Équipements agricoles modernes</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-secondary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Technologies adaptées aux exploitations</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-secondary text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Formation à l'utilisation des outils</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center text-xs text-secondary">
                            <i class="fas fa-microchip mr-1"></i> Innovation technique
                        </span>
                    </div>
                </div>
            </div>

            <!-- Carte 3 - Formation & Certification -->
            <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-accent/5 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative bg-gradient-to-r from-accent to-primary p-6 text-white">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-bold text-white/30">03</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Formation & <br>Certification</h3>
                </div>
                
                <div class="relative p-6">
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Accompagnement expert en agriculture biologique et certifications internationales.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-accent text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Formations pratiques sur le terrain</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-accent text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Certifications CE/EOS, NOP et SPG</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-accent text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Accompagnement personnalisé</span>
                        </li>
                        <li class="flex items-start space-x-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-accent text-sm mt-0.5 flex-shrink-0"></i>
                            <span>Suivi technique continu</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center text-xs text-accent">
                            <i class="fas fa-certificate mr-1"></i> Certification internationale
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bannière Productions Biologiques Certifiées -->
        <div class="mt-16 relative overflow-hidden rounded-2xl shadow-xl">
            <!-- Arrière-plan animé -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative p-8 md:p-10 text-center text-white">
                <div class="inline-flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-certificate text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold mb-3">Productions Biologiques Certifiées</h3>
                <p class="text-white/90 max-w-2xl mx-auto">
                    Nous produisons également des légumes et fruits certifiés biologiques, garantissant la qualité et l'intégrité selon les standards internationaux.
                </p>
                
                <!-- Bouton -->
                <div class="mt-6">
                    <a href="{{ route('galerie') }}" class="inline-flex items-center space-x-2 bg-white text-primary px-6 py-2 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        <span>Découvrir nos productions</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chiffres clés -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16">
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-seedling text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">50+</div>
                <div class="text-sm text-gray-500">Variétés de semences</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-certificate text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">100%</div>
                <div class="text-sm text-gray-500">Bio et local</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-chalkboard-user text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">500+</div>
                <div class="text-sm text-gray-500">Agriculteurs formés</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-tractor text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">10+</div>
                <div class="text-sm text-gray-500">Années d'expertise</div>
            </div>
        </div>
    </div>
</section>

<!-- ----------------------------------------------------
 /-------------------Section À Propos--------------------/ 
------------------------------------------------------------>
<section id="about" class="py-24 bg-gradient-to-br from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center mb-4">
                <div class="h-px w-12 bg-primary"></div>
                <i class="fas fa-seedling text-primary mx-3 text-2xl"></i>
                <div class="h-px w-12 bg-primary"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-dark mb-4">À Propos de <span class="text-primary">Tropi-Techno</span></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Entreprise de biotechnologies agricoles au service de l'agriculture biologique au Togo</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
            <!-- Colonne gauche - Histoire -->
            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-history text-primary text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark">Notre Histoire</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Notre Entreprise de biotechnologies agricoles créée le <span class="font-semibold text-primary">31 décembre 2018</span> est basée à <span class="font-semibold text-primary">Sokodé</span> dans la région centrale du Togo. Elle dispose d'un personnel opérationnel de <span class="font-semibold text-primary">12 membres</span> répartis dans les trois régions d'exploitations <span class="font-semibold text-primary">(Centrale, Kara et Savanes)</span>.
                    </p>
                    
                    <!-- Stats cards -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center border border-green-100">
                            <i class="fas fa-calendar-alt text-primary text-2xl mb-2"></i>
                            <div class="text-2xl font-bold text-primary">31 Déc 2018</div>
                            <div class="text-sm text-gray-600">Date de création</div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 text-center border border-blue-100">
                            <i class="fas fa-users text-primary text-2xl mb-2"></i>
                            <div class="text-2xl font-bold text-primary">12</div>
                            <div class="text-sm text-gray-600">Membres</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 text-center border border-purple-100">
                            <i class="fas fa-map-marker-alt text-primary text-2xl mb-2"></i>
                            <div class="text-2xl font-bold text-primary">Sokodé</div>
                            <div class="text-sm text-gray-600">Siège social</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 text-center border border-orange-100">
                            <i class="fas fa-globe-africa text-primary text-2xl mb-2"></i>
                            <div class="text-2xl font-bold text-primary">3</div>
                            <div class="text-sm text-gray-600">Régions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Image/Innovation -->
            <div class="space-y-8">
                <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl p-8 text-white shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Notre Innovation</h3>
                    </div>
                    <p class="text-white/90 leading-relaxed">
                        Nous rendons disponible des <span class="font-semibold">semences bio-locales</span> caractérisées par leurs bonnes performances productives, leur adaptation aux réalités climatiques diverses et leur intégrité biologique qui répond aux exigences de la certification biologique des exploitations.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-accent text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark">Nos Objectifs</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Contribuer à optimiser la productivité agricole des exploitations agricoles et alimentaires certifiées biologiques.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stratégies et Innovation détaillée -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <!-- Stratégies -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-primary">
                <div class="flex items-center mb-4">
                    <i class="fas fa-handshake text-primary text-2xl mr-3"></i>
                    <h3 class="text-2xl font-bold text-dark">Nos Stratégies</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Servir les acteurs en amont et en aval des filières agricoles et alimentaires certifiées biologiques ou écologiques à partir des partenariats locaux et solidaires.
                </p>
            </div>

            <!-- Objectifs -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-secondary">
                <div class="flex items-center mb-4">
                    <i class="fas fa-bullseye text-secondary text-2xl mr-3"></i>
                    <h3 class="text-2xl font-bold text-dark">Nos Engagements</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-600">Qualité certifiée</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-600">100% Bio local</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-600">Traçabilité totale</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-600">Partenariats solidaires</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Innovation détaillée - Carte complète -->
        <div class="bg-gradient-to-b from-primary to-secondary rounded-2xl overflow-hidden shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-3">
                <div class="lg:col-span-2 p-8 lg:p-12">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-microchip text-accent text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Notre Innovation</h3>
                            <p class="text-white/70 text-sm">Une approche unique pour l'agriculture biologique</p>
                        </div>
                    </div>
                    <p class="text-white/90 leading-relaxed mb-6">
                        Cette innovation contribue à optimiser la productivité agricole des exploitations certifiées biologiques à travers :
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-chart-line text-accent mt-1"></i>
                            <span class="text-white/80 text-sm">L'amélioration des rendements</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-leaf text-accent mt-1"></i>
                            <span class="text-white/80 text-sm">L'adoption de bonnes pratiques productives</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-shield-alt text-accent mt-1"></i>
                            <span class="text-white/80 text-sm">Le renforcement des capacités de résilience</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-chart-simple text-accent mt-1"></i>
                            <span class="text-white/80 text-sm">L'amélioration de la compétitivité commerciale</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 p-8 lg:p-12 flex flex-col justify-center items-center text-center">
                    <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-trophy text-accent text-3xl"></i>
                    </div>
                    <p class="text-white font-semibold text-lg mb-2">Impact Socio-économique</p>
                    <p class="text-white/70 text-sm">Un impact significatif sur les communautés d'acteurs des productions biologiques dans notre pays.</p>
                </div>
            </div>
        </div>

        <!-- Chiffres clés supplémentaires -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16">
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-tractor text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">500+</div>
                <div class="text-sm text-gray-500">Agriculteurs formés</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-seedling text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">50+</div>
                <div class="text-sm text-gray-500">Variétés de semences</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-calendar-check text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">10+</div>
                <div class="text-sm text-gray-500">Années d'expérience</div>
            </div>
            <div class="text-center group cursor-pointer">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-certificate text-primary text-2xl group-hover:text-white transition-colors"></i>
                </div>
                <div class="text-2xl font-bold text-dark">100%</div>
                <div class="text-sm text-gray-500">Bio et local</div>
            </div>
        </div>
    </div>
</section>

<!-- ---------------------------------------------------
 /-------------------Section Fondateur---------/
 -------------------------------------------------- -->
<section id="fondateur" class="py-24 bg-gradient-to-br from-gray-50 to-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de section -->
        <div class="text-center mb-16">
            <!-- Décoration -->
            <!-- <div class="inline-flex items-center justify-center mb-4">
                <div class="w-12 h-px bg-primary"></div>
                <div class="mx-3 p-2 bg-primary/10 rounded-full">
                    <i class="fas fa-leaf text-primary text-xl"></i>
                </div>
                <div class="w-12 h-px bg-primary"></div>
            </div> -->
            <h2 class="text-4xl md:text-5xl font-bold text-dark mb-4">
                Rencontrez Notre <span class="text-primary">Fondateur</span>
            </h2>
            <div class="w-24 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- Colonne gauche - Photo et infos -->
            <div class="space-y-6 animate-fade-in-up">
                <!-- Carte photo principale -->
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl">
                        <div class="relative h-96 overflow-hidden">
                            <img src="{{ asset('images/img5.png') }}"  
                                 alt="Ousmane Labodja"
                                 class="w-full h-full  object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <div class="flex items-center space-x-3 mb-2">
                                <i class="fas fa-certificate text-accent"></i>
                                <span class="text-sm font-medium">Fondateur & DG</span>
                            </div>
                            <h3 class="text-2xl font-bold">Ousmane Labodja</h3>
                            <p class="text-white/80 text-sm">🌱 Agronome | Expert en agriculture biologique</p>
                        </div>
                    </div>
                </div>

                <!-- Badges de réalisations -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 group">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-graduation-cap text-primary text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <p class="text-xs text-gray-500">Formation</p>
                        <p class="font-semibold text-dark">Licence Pro</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 group">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-seedling text-primary text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <p class="text-xs text-gray-500">Innovation</p>
                        <p class="font-semibold text-dark">Gombo Gnoukpamoutawoulou</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 group">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-chart-line text-primary text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <p class="text-xs text-gray-500">Leadership</p>
                        <p class="font-semibold text-dark">DG & Chargé de Programme</p>
                    </div>
                </div>

                <!-- Citation -->
                <div class="bg-gradient-to-r from-primary/5 to-secondary/5 rounded-xl p-5 border-l-4 border-primary">
                    <i class="fas fa-quote-left text-primary/30 text-2xl mb-2"></i>
                    <p class="text-gray-600 italic">"L'agriculture biologique est l'avenir de l'Afrique. Nous devons innover localement pour nourrir le monde durablement."</p>
                    <p class="text-primary font-semibold mt-2">— Ousmane Labodja</p>
                </div>
            </div>

            <!-- Colonne droite - Bio et onglets interactifs -->
            <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.2s">
                <!-- Titre et sous-titre -->
                <div>
                    <div class="inline-flex items-center space-x-2 bg-primary/10 px-4 py-1 rounded-full mb-4">
                        <i class="fas fa-star text-primary text-xs"></i>
                        <span class="text-primary text-sm font-medium">Un Pionnier de l'Innovation Agricole</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed">
                        Ousmane Labodja est un agronome passionné et un acteur clé de l'agriculture biologique en Afrique de l'Ouest. 
                        À la tête de <span class="font-semibold text-primary">TROPI-TECHNO Sarl</span>, il œuvre depuis plus d'une décennie 
                        à la transformation durable des systèmes agricoles tropicaux.
                    </p>
                </div>

                <!-- Onglets interactifs -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Navigation des onglets -->
                    <div class="grid grid-cols-3 border-b">
                        <button class="tab-btn py-4 px-4 text-center font-semibold transition-all duration-300 relative" data-tab="vision">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-eye text-lg"></i>
                                <span class="hidden sm:inline">Une vision durable</span>
                                <span class="sm:hidden">Vision</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary transition-all duration-300"></div>
                        </button>
                        <button class="tab-btn py-4 px-4 text-center font-semibold transition-all duration-300 text-gray-500" data-tab="approche">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-lightbulb text-lg"></i>
                                <span class="hidden sm:inline">Approche innovante</span>
                                <span class="sm:hidden">Approche</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary transition-all duration-300 scale-x-0"></div>
                        </button>
                        <button class="tab-btn py-4 px-4 text-center font-semibold transition-all duration-300 text-gray-500" data-tab="expertise">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-trophy text-lg"></i>
                                <span class="hidden sm:inline">Expertise reconnue</span>
                                <span class="sm:hidden">Expertise</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary transition-all duration-300 scale-x-0"></div>
                        </button>
                    </div>

                    <!-- Contenu des onglets -->
                    <div class="p-6 min-h-[220px] bg-gradient-to-br from-gray-50 to-white">
                        <!-- Vision -->
                        <div id="tab-vision" class="tab-content">
                            <div class="flex items-start space-x-3 mb-4">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-eye text-primary"></i>
                                </div>
                                <h4 class="text-xl font-bold text-dark">Une vision durable</h4>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Ousmane Labodja incarne une nouvelle génération d'agronomes africains : ancrés dans les réalités locales, 
                                porteurs d'innovation, et résolument tournés vers un avenir agricole plus sain, plus équitable et plus durable.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Durable</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Innovant</span>
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">Équitable</span>
                            </div>
                        </div>

                        <!-- Approche -->
                        <div id="tab-approche" class="tab-content hidden">
                            <div class="flex items-start space-x-3 mb-4">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-lightbulb text-primary"></i>
                                </div>
                                <h4 class="text-xl font-bold text-dark">Approche innovante</h4>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Son approche repose sur la recherche participative, l'expérimentation communautaire, 
                                la vulgarisation de pratiques agroécologiques adaptées aux réalités rurales, et l'accompagnement 
                                technique des producteurs.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Recherche participative</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Expérimentation communautaire</span>
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">Vulgarisation</span>
                            </div>
                        </div>

                        <!-- Expertise -->
                        <div id="tab-expertise" class="tab-content hidden">
                            <div class="flex items-start space-x-3 mb-4">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-trophy text-primary"></i>
                                </div>
                                <h4 class="text-xl font-bold text-dark">Expertise reconnue</h4>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                Plus d'une décennie d'expérience dans la transformation durable des systèmes agricoles tropicaux, 
                                spécialisé dans la production et certification biologique, la gestion des semences et l'aménagement 
                                des ressources naturelles.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">10+ ans d'expérience</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Certification bio</span>
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">Gestion des semences</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques clés -->
                <div class="grid grid-cols-3 mb-15 gap-4">
                    <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                        <div class="text-2xl font-bold text-primary">10+</div>
                        <p class="text-xs text-gray-500">Années d'expertise</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                        <div class="text-2xl font-bold text-primary">500+</div>
                        <p class="text-xs text-gray-500">Agriculteurs formés</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                        <div class="text-2xl font-bold text-primary">50+</div>
                        <p class="text-xs text-gray-500">Variétés de semences</p>
                    </div>
                </div>

                <!-- Bouton collaborer -->
                <div class="flex justify-center">
                    <a href="tel:93266657" class="group inline-flex items-center space-x-2 bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <span>Collaborer avec notre fondateur</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /**************************************************
    Style pour la section Rencontrer notre Fondadeur
    *********************************************** */
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
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .tab-btn.active {
        color: #2d6a4f;
    }
    
    .tab-btn.active .absolute {
        transform: scaleX(1);
    }
    
    .tab-content {
        transition: opacity 0.3s ease;
    }
    
    .tab-content.hidden {
        display: none;
    }

    /****************** *****************************
     Style pour la section Hero 
     *********************************************/
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
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .animate-slide-up {
        animation: slideUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    /* Animation des dots actifs */
    .dot.active {
        background-color:rgb(8, 197, 8);
        width: 12px;
        height: 12px;
        box-shadow: 0 0 10px rgba(8, 138, 26, 0.5);
    }
    
    /* Transition douce des slides */
    .slide {
        transition: opacity 1s ease-in-out;
    }
</style>

<!-- ------------------------------------------------------
JavaScript pour la section Rencontrer notre Fondadeur 
 -------------------------------------------------->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = {
            vision: document.getElementById('tab-vision'),
            approche: document.getElementById('tab-approche'),
            expertise: document.getElementById('tab-expertise')
        };
        
        function activateTab(tabId) {
            // Cacher tous les contenus
            Object.values(contents).forEach(content => {
                if (content) content.classList.add('hidden');
            });
            
            // Afficher le contenu sélectionné
            if (contents[tabId]) {
                contents[tabId].classList.remove('hidden');
            }
            
            // Mettre à jour les styles des boutons
            tabs.forEach(tab => {
                const indicator = tab.querySelector('.absolute');
                if (tab.dataset.tab === tabId) {
                    tab.classList.add('active');
                    tab.classList.remove('text-gray-500');
                    tab.classList.add('text-primary');
                    if (indicator) indicator.style.transform = 'scaleX(1)';
                } else {
                    tab.classList.remove('active');
                    tab.classList.add('text-gray-500');
                    tab.classList.remove('text-primary');
                    if (indicator) indicator.style.transform = 'scaleX(0)';
                }
            });
        }
        
        // Ajouter les événements de clic
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.dataset.tab;
                if (tabId) activateTab(tabId);
            });
        });
        
        // Activer l'onglet par défaut (vision)
        activateTab('vision');
    });
</script>

<!-- -----------------------------------------------------------------------------
 JavaScript pour la section HERO
 ---------------------------------------------------------------------------- -->
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;
        let autoSlideInterval;
        
        function showSlide(index) {
            // Normaliser l'index
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            
            // Cacher tous les slides
            slides.forEach(slide => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            });
            
            // Afficher le slide actuel
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
            
            // Mettre à jour les dots
            dots.forEach(dot => {
                dot.classList.remove('active');
                dot.classList.add('bg-white/50');
                dot.classList.remove('bg-accent');
            });
            dots[index].classList.add('active');
            dots[index].classList.remove('bg-white/50');
            dots[index].classList.add('bg-accent');
            
            currentSlide = index;
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
            resetAutoSlide();
        }
        
        function prevSlide() {
            showSlide(currentSlide - 1);
            resetAutoSlide();
        }
        
        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, 6000);
        }
        
        // Événements
        document.getElementById('nextSlide').addEventListener('click', nextSlide);
        document.getElementById('prevSlide').addEventListener('click', prevSlide);
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetAutoSlide();
            });
        });
        
        // Démarrer le carrousel
        autoSlideInterval = setInterval(() => {
            nextSlide();
        }, 6000);
        
        // Pause au survol
        const heroSection = document.getElementById('home');
        heroSection.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });
        
        heroSection.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, 6000);
        });
    });
</script>
@endsection