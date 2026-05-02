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

    <!-- Mobile Menu - Version ultra simple -->
<div id="mobile-menu" style="display: none; position: absolute; top: 80px; left: 0; right: 0; background: white; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <div class="p-4 space-y-2">
        <a href="{{ route('welcome') }}" class="block p-3 text-dark hover:bg-primary hover:text-white rounded-lg">Accueil</a>
        <a href="{{ route('actualites') }}" class="block p-3 text-dark hover:bg-primary hover:text-white rounded-lg">Actualités</a>
        <a href="#about" class="block p-3 text-dark hover:bg-primary hover:text-white rounded-lg">À Propos</a>
        <a href="{{ route('galerie') }}" class="block p-3 text-dark hover:bg-primary hover:text-white rounded-lg">Galerie</a>
        <a href="{{ route('contact') }}" class="block p-3 text-dark hover:bg-primary hover:text-white rounded-lg">Contact</a>
    </div>
</div>

<script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('mobile-menu-icon');
    
    btn.addEventListener('click', function() {
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            menu.style.display = 'none';
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
</script>