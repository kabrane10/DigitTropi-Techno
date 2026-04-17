<!-- Bouton WhatsApp Flottant -->
<div id="whatsappButton" class="fixed bottom-6 right-6 z-50 group">
    <div class="relative">
        <!-- Tooltip -->
        <div class="absolute bottom-16 right-0 mb-2 w-56 bg-gray-800 text-white text-sm rounded-lg p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            <i class="fas fa-comment-dots mr-1"></i> Besoin d'aide ? Contactez-nous
            <div class="absolute -bottom-1 right-4 w-2 h-2 bg-gray-800 transform rotate-45"></div>
        </div>
        
        <!-- Pulse animation -->
        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-75"></div>
        
        <!-- Bouton principal -->
        <a href="https://wa.me/22892952961?text={{ urlencode(session('message')) }}" 
           target="_blank"
           class="flex items-center justify-center w-14 h-14 bg-green-500 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300 transform hover:scale-110"
           id="whatsappLink">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </a>
    </div>
</div>

<style>
    @keyframes pulse-ring {
        0% {
            transform: scale(0.8);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    
    .whatsapp-pulse {
        animation: pulse-ring 1.5s infinite;
    }
</style>

<script>
    // Changer le message en fonction de la page
    document.addEventListener('DOMContentLoaded', function() {
        const whatsappLink = document.getElementById('whatsappLink');
        if (whatsappLink) {
            // Vous pouvez modifier le message ici selon la page
            let currentUrl = window.location.href;
            let message = "Bonjour, je viens de visiter votre site web Tropi-Techno ({{ config('app.url') }}) et j'aimerais avoir plus d'informations sur vos services.";
            
            // Personnaliser le message selon la page
            if (currentUrl.includes('/galerie')) {
                message = "Bonjour, j'ai vu vos photos sur la galerie de Tropi-Techno et je suis intéressé par vos services agricoles. Pouvez-vous me donner plus d'informations ?";
            } else if (currentUrl.includes('/actualites')) {
                message = "Bonjour, j'ai lu vos actualités sur Tropi-Techno et je souhaiterais en savoir plus sur vos activités. Pouvez-vous me contacter ?";
            } else if (currentUrl.includes('/contact')) {
                message = "Bonjour, je viens de remplir le formulaire de contact sur Tropi-Techno et je souhaite confirmer la réception de mon message.";
            } else if (currentUrl.includes('/admin')) {
                message = "Bonjour, je suis administrateur de Tropi-Techno et j'ai besoin d'assistance technique.";
            } else if (currentUrl.includes('/animateur')) {
                message = "Bonjour, je suis animateur chez Tropi-Techno et j'ai besoin d'aide sur la plateforme.";
            } else if (currentUrl.includes('/agent')) {
                message = "Bonjour, je suis agent terrain chez Tropi-Techno et j'ai besoin d'assistance.";
            }
            
            whatsappLink.href = "https://wa.me/22892952961?text=" + encodeURIComponent(message);
        }
    });
</script>