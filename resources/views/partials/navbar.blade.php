<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2">
                    <!-- <i class="fas fa-leaf text-primary text-3xl animate-pulse-slow"></i> -->
                    <img src="{{ asset('images/img6.png') }}" class="h-8 sm:h-10 md:h-16 rounded-full w-auto object-contain">
                    <span class="font-poppins font-bold text-xl text-dark">Tropi-Techno</span>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="{{ route('welcome') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Accueil</a>
                <a href="{{ route('actualites') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Actualités</a>
                <a href="#about" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">À Propos</a>
                <a href="{{ route('galerie') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Galerie</a>
                <a href="{{ route('contact') }}"  class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Contact</a>
            </div>
            
            <!-- CTA Button -->
            <div class="hidden md:block">
                <a href="#contact" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition-all duration-300 transform hover:scale-105">
                    Nous Contacter
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-dark focus:outline-none">
                    <i id="mobile-menu-icon" class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('welcome') }}" class="block px-3 py-2 text-dark hover:text-primary font-medium">Accueil</a>
            <a href="{{ route('actualites') }}" class="block px-3 py-2 text-dark hover:text-primary font-medium">Actualités</a>
            <a href="#about" class="block px-3 py-2 text-dark hover:text-primary font-medium">À Propos</a>
            <a href="{{ route('galerie') }}" class="block px-3 py-2 text-dark hover:text-primary font-medium">Galerie</a>
            <a href="{{ route('contact') }}"  class="block px-3 py-2 text-dark hover:text-primary font-medium">Contact</a>
            <div class="pt-2">
                <a href="{{ route('contact') }}" class="block text-center bg-primary text-white px-4 py-3 rounded-lg hover:bg-secondary transition">
                    Nous Contacter
                </a>
            </div>
        </div>
    </div>
</nav>
<script>
    // Menu mobile toggle
    document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            console.log("Le bouton a été cliqué !"); // Ajoutez ceci
            const isHidden = mobileMenu.classList.contains('hidden');
            
            // Toggle menu
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icône (seulement si elle existe)
            if (mobileMenuIcon) {
                if (isHidden) {
                    mobileMenuIcon.classList.replace('fa-bars', 'fa-times');
                } else {
                    mobileMenuIcon.classList.replace('fa-times', 'fa-bars');
                }
            }
        });
        
        // Fermer le menu au clic sur un lien
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                if (mobileMenuIcon) {
                    mobileMenuIcon.classList.replace('fa-times', 'fa-bars');
                }
            });
        });
    }
});
</script>

<style>
    /* Animation pour le menu mobile */
    #mobile-menu {
        transition: all 0.3s ease;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    #mobile-menu a {
        transition: all 0.2s ease;
    }
    
    #mobile-menu-button {
        transition: all 0.2s ease;
    }
    
    #mobile-menu-button:active {
        transform: scale(0.95);
    }
</style>