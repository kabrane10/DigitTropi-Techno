<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/img6.png') }}" class="h-8 sm:h-10 md:h-12 rounded-full w-auto object-contain">
                    <span class="font-poppins font-bold text-xl text-dark">Tropi-Techno</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('welcome') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Accueil</a>
                <a href="{{ route('actualites') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Actualités</a>
                <a href="#about" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">À Propos</a>
                <a href="{{ route('galerie') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Galerie</a>
                <a href="{{ route('contact') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Contact</a>
            </div>
            
            <!-- CTA Button -->
            <div class="hidden md:block">
                <a href="{{ route('contact') }}" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition-all duration-300 transform hover:scale-105">
                    Nous Contacter
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-dark focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition">
                    <i id="mobile-menu-icon" class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu - Version corrigée -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg border-t border-gray-100" style="position: absolute; left: 0; right: 0; top: 80px;">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('welcome') }}" class="block px-4 py-3 text-dark hover:bg-primary hover:text-white rounded-lg transition-all duration-300 font-medium">Accueil</a>
            <a href="{{ route('actualites') }}" class="block px-4 py-3 text-dark hover:bg-primary hover:text-white rounded-lg transition-all duration-300 font-medium">Actualités</a>
            <a href="#about" class="block px-4 py-3 text-dark hover:bg-primary hover:text-white rounded-lg transition-all duration-300 font-medium">À Propos</a>
            <a href="{{ route('galerie') }}" class="block px-4 py-3 text-dark hover:bg-primary hover:text-white rounded-lg transition-all duration-300 font-medium">Galerie</a>
            <a href="{{ route('contact') }}" class="block px-4 py-3 text-dark hover:bg-primary hover:text-white rounded-lg transition-all duration-300 font-medium">Contact</a>
            <div class="pt-3 mt-2 border-t border-gray-100">
                <a href="{{ route('contact') }}" class="block text-center bg-primary text-white px-4 py-3 rounded-lg hover:bg-secondary transition-all duration-300">
                    Nous Contacter
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Menu mobile toggle - Version améliorée
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');
        
        if (mobileMenuButton && mobileMenu) {
            // Afficher/masquer le menu
            mobileMenuButton.addEventListener('click', function(event) {
                event.stopPropagation();
                
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                    if (mobileMenuIcon) {
                        mobileMenuIcon.classList.remove('fa-bars');
                        mobileMenuIcon.classList.add('fa-times');
                    }
                    // Empêcher le scroll du body
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenu.classList.add('hidden');
                    if (mobileMenuIcon) {
                        mobileMenuIcon.classList.remove('fa-times');
                        mobileMenuIcon.classList.add('fa-bars');
                    }
                    document.body.style.overflow = '';
                }
            });
            
            // Fermer le menu au clic sur un lien
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    if (mobileMenuIcon) {
                        mobileMenuIcon.classList.remove('fa-times');
                        mobileMenuIcon.classList.add('fa-bars');
                    }
                    document.body.style.overflow = '';
                });
            });
            
            // Fermer le menu en cliquant en dehors
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        if (mobileMenuIcon) {
                            mobileMenuIcon.classList.remove('fa-times');
                            mobileMenuIcon.classList.add('fa-bars');
                        }
                        document.body.style.overflow = '';
                    }
                }
            });
        }
    });
</script>

<style>
    /* Styles pour le menu mobile */
    #mobile-menu {
        transition: all 0.3s ease-in-out;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    #mobile-menu.hidden {
        display: none;
    }
    
    #mobile-menu a {
        transition: all 0.2s ease;
    }
    
    #mobile-menu-button:active {
        transform: scale(0.95);
    }
    
    /* Animation d'entrée */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #mobile-menu:not(.hidden) {
        animation: slideDown 0.3s ease-out;
    }
</style>