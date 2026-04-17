<footer class="bg-dark text-white pt-16 pb-8">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Company Info -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <!-- <i class="fas fa-seedling text-secondary text-2xl"></i> -->
                    <img src="{{ asset('images/img6.png') }}" class="h-12 w-auto rounded-full">
                    <span class="font-poppins font-bold text-xl">Tropi-Techno</span>
                </div>
                <p class="text-gray-300 mb-4">Entreprise de biotechnologies agricoles au service de l'agriculture biologique au Togo.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-secondary transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-300 hover:text-secondary transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-300 hover:text-secondary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="font-poppins font-semibold text-lg mb-4">Liens Rapides</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route ('welcome') }}" class="text-gray-300 hover:text-secondary transition-colors">Accueil</a></li>
                    <li><a href="{{ route ('actualites') }}" class="text-gray-300 hover:text-secondary transition-colors">Actualités</a></li>
                    <li><a href="{{ route ('galerie') }}" class="text-gray-300 hover:text-secondary transition-colors">Galerie</a></li>
                    <li><a href="{{ route ('contact') }}" class="text-gray-300 hover:text-secondary transition-colors">Contact</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="font-poppins font-semibold text-lg mb-4">Contact</h3>
                <ul class="space-y-2 text-gray-300">
                    <li><i class="fas fa-map-marker-alt text-secondary mr-2"></i> RN:17, Bamabodolo, Sokodé-Togo</li>
                    <li><i class="fas fa-phone text-secondary mr-2"></i> +228 25 50 63 12</li>
                    <li><i class="fas fa-envelope text-secondary mr-2"></i> tropitechno@admin.com</li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div>
                <h3 class="font-poppins font-semibold text-lg mb-4">Newsletter</h3>
                <p class="text-gray-300 mb-4">Recevez nos actualités et offres spéciales</p>
                <form class="flex flex-col space-y-2">
                    <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-lg text-dark">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">S'abonner</button>
                </form>
            </div>
        </div>
           <!-- Dans le footer, ajoutez un lien discret -->
<div class="text-center mt-5 mb-5 pt-5 border-t border-gray-700">
    <a href="{{ route('role.selection') }}" class="text-gray-500 text-xl hover:text-gray-300">
        <i class="fas fa-lock mr-1"></i> Accès administration
    </a>
</div>
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Tropi-Techno Sarl. Tous droits réservés.</p>
        </div>
    </div>
</footer>