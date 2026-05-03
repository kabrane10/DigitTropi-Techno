<nav class="bg-white shadow-lg fixed w-full z-[100] top-0">
    <!-- Barre principale (Toujours au premier plan) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-white relative z-[101]">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2">
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
                <a href="{{ route('contact') }}" class="text-dark hover:bg-primary px-3 py-1 hover:text-white rounded-full transition-colors duration-300 font-medium">Contact</a>
            </div>
            
            <!-- CTA Button Desktop -->
            <div class="hidden md:block">
                <a href="#contact" class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition-all duration-300 transform hover:scale-105">
                    Nous Contacter
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-dark focus:outline-none p-2 cursor-pointer">
                    <i id="mobile-menu-icon" class="fas fa-bars text-3xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Positionné de force en dessous) -->
    <div id="mobile-menu" class="absolute left-0 w-full bg-white border-t border-gray-200 shadow-2xl z-[99]" style="display: none; top: 100%;">
        <div class="px-4 py-4 space-y-2 pb-8">
            <a href="{{ route('welcome') }}" class="block px-4 py-3 text-lg font-bold text-gray-900 hover:bg-gray-100 rounded-lg">Accueil</a>
            <a href="{{ route('actualites') }}" class="block px-4 py-3 text-lg font-bold text-gray-900 hover:bg-gray-100 rounded-lg">Actualités</a>
            <a href="#about" class="block px-4 py-3 text-lg font-bold text-gray-900 hover:bg-gray-100 rounded-lg">À Propos</a>
            <a href="{{ route('galerie') }}" class="block px-4 py-3 text-lg font-bold text-gray-900 hover:bg-gray-100 rounded-lg">Galerie</a>
            <a href="{{ route('contact') }}" class="block px-4 py-3 text-lg font-bold text-gray-900 hover:bg-gray-100 rounded-lg">Contact</a>
            
            <div class="pt-4 mt-2 border-t border-gray-200">
                <a href="{{ route('contact') }}" class="block text-center bg-primary text-white px-4 py-3 rounded-lg font-bold text-lg">
                    Nous Contacter
                </a>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('mobile-menu-icon');

        if (btn && menu) {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // Empêche le saut de page
                
                // Utilisation de style.display pour bypasser les conflits de production
                if (menu.style.display === 'none' || menu.style.display === '') {
                    // Ouvrir
                    menu.style.display = 'block';
                    if (icon) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    }
                } else {
                    // Fermer
                    menu.style.display = 'none';
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });

            // Fermer le menu lors du clic sur un lien
            const links = menu.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function() {
                    menu.style.display = 'none';
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });
        }
    });
</script>