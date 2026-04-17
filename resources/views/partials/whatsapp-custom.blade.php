<!-- WhatsApp avec message dynamique -->
<div id="whatsappWidget" class="fixed bottom-6 right-6 z-50">
    <div class="relative">
        <!-- Indicateur de présence -->
        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
        
        <a href="#" 
           id="whatsappDynamicLink"
           class="flex items-center justify-center w-14 h-14 bg-green-500 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300 transform hover:scale-110"
           target="_blank">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </a>
    </div>
</div>

<script>
    // Messages personnalisés par page
    const messages = {
        '/': 'Bonjour, je visite votre site Tropi-Techno et je souhaite en savoir plus sur vos services agricoles.',
        '/galerie': 'Bonjour, j\'ai vu vos photos sur la galerie. Je suis intéressé par vos activités agricoles.',
        '/actualites': 'Bonjour, j\'ai lu vos actualités. Pouvez-vous me donner plus d\'informations ?',
        '/contact': 'Bonjour, je viens de remplir le formulaire de contact et je souhaite confirmer la réception.',
        '/admin': 'Bonjour, je suis administrateur et j\'ai besoin d\'assistance technique.',
        '/animateur': 'Bonjour, je suis animateur et j\'ai besoin d\'aide sur la plateforme.',
        '/agent': 'Bonjour, je suis agent terrain et je rencontre un problème.',
        '/controleur': 'Bonjour, je suis contrôleur et j\'ai besoin d\'informations.'
    };
    
    // Messages pour les producteurs (si connecté)
    const producteurMessage = 'Bonjour, je suis producteur et je souhaite obtenir des informations sur les semences et les crédits agricoles.';
    
    // Récupérer le chemin actuel
    let currentPath = window.location.pathname;
    let message = messages[currentPath] || messages['/'];
    
    // Vérifier si l'utilisateur est un producteur (via session ou paramètre)
    if (window.location.search.includes('producteur=true')) {
        message = producteurMessage;
    }
    
    // Ajouter le numéro de téléphone et la date
    message += ' ' + new Date().toLocaleDateString('fr-FR');
    
    // Mettre à jour le lien
    const link = document.getElementById('whatsappDynamicLink');
    if (link) {
        link.href = 'https://wa.me/22892952961?text=' + encodeURIComponent(message);
    }
</script>