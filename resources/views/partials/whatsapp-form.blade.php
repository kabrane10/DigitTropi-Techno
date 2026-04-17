<!-- WhatsApp avec prémessage personnalisable -->
<div id="whatsappModal" class="fixed bottom-6 right-6 z-50">
    <!-- Bouton -->
    <button onclick="toggleWhatsAppModal()" class="flex items-center justify-center w-14 h-14 bg-green-500 rounded-full shadow-lg hover:bg-green-600 transition-all duration-300">
        <i class="fab fa-whatsapp text-white text-3xl"></i>
    </button>
    
    <!-- Modal de message -->
    <div id="whatsappModalContent" class="absolute bottom-16 right-0 mb-2 w-80 bg-white rounded-xl shadow-xl hidden overflow-hidden">
        <div class="bg-green-500 text-white p-3 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fab fa-whatsapp text-xl mr-2"></i>
                <span class="font-semibold">Envoyer un message</span>
            </div>
            <button onclick="toggleWhatsAppModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-4">
            <p class="text-sm text-gray-600 mb-3">Préparez votre message :</p>
            
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Votre nom</label>
                <input type="text" id="userName" placeholder="Votre nom" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Votre message</label>
                <textarea id="userMessage" rows="4" placeholder="Décrivez votre demande..." class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
            </div>
            
            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                <span><i class="fas fa-lock mr-1"></i> Message sécurisé</span>
                <span><i class="fab fa-whatsapp mr-1"></i> WhatsApp Business</span>
            </div>
            
            <button onclick="sendWhatsAppMessage()" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition font-semibold">
                <i class="fab fa-whatsapp mr-2"></i>Envoyer sur WhatsApp
            </button>
        </div>
    </div>
</div>

<script>
    function toggleWhatsAppModal() {
        const modal = document.getElementById('whatsappModalContent');
        modal.classList.toggle('hidden');
    }
    
    function sendWhatsAppMessage() {
        const userName = document.getElementById('userName').value || 'Client';
        const userMessage = document.getElementById('userMessage').value;
        
        if (!userMessage) {
            alert('Veuillez saisir votre message');
            return;
        }
        
        const pageUrl = window.location.href;
        const message = `Bonjour, je suis ${userName}.%0A%0A${encodeURIComponent(userMessage)}%0A%0A---%0AJe vous contacte depuis: ${pageUrl}`;
        
        window.open(`https://wa.me/22892952961?text=${message}`, '_blank');
        toggleWhatsAppModal();
        
        // Réinitialiser
        document.getElementById('userMessage').value = '';
    }
    
    // Fermer en cliquant en dehors
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('whatsappModalContent');
        const button = event.target.closest('#whatsappModal button');
        
        if (!modal.contains(event.target) && !button && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
</script>