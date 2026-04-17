<!-- Multi-contacts WhatsApp -->
<div class="fixed bottom-6 right-6 z-50">
    <!-- Bouton principal -->
    <div class="relative">
        <input type="checkbox" id="whatsappToggle" class="hidden peer">
        <label for="whatsappToggle" class="flex items-center justify-center w-14 h-14 bg-green-500 rounded-full shadow-lg cursor-pointer hover:bg-green-600 transition-all duration-300">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </label>
        
        <!-- Menu des contacts -->
        <div class="absolute bottom-16 right-0 mb-2 w-64 bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 opacity-0 invisible peer-checked:opacity-100 peer-checked:visible">
            <div class="bg-green-500 text-white p-3">
                <h3 class="font-semibold">Contactez-nous</h3>
                <p class="text-xs opacity-90">Choisissez un service</p>
            </div>
            
            <!-- Contact Directeur Général -->
            <a href="https://wa.me/22892952961?text={{ urlencode('Bonjour, je souhaite parler au Directeur Général concernant un partenariat.') }}" 
               target="_blank"
               class="flex items-center p-3 hover:bg-gray-50 transition border-b">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-green-600"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-semibold text-dark">Ousmane Labodja</p>
                    <p class="text-xs text-gray-500">Directeur Général</p>
                </div>
                <i class="fab fa-whatsapp text-green-500 text-xl"></i>
            </a>
            
            <!-- Contact Support technique -->
            <a href="https://wa.me/22825506312?text={{ urlencode('Bonjour, j\'ai besoin d\'assistance technique sur la plateforme Tropi-Techno.') }}" 
               target="_blank"
               class="flex items-center p-3 hover:bg-gray-50 transition border-b">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-headset text-blue-600"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-semibold text-dark">Support Technique</p>
                    <p class="text-xs text-gray-500">Assistance 24/7</p>
                </div>
                <i class="fab fa-whatsapp text-green-500 text-xl"></i>
            </a>
            
            <!-- Contact Commercial -->
            <a href="https://wa.me/22898988013?text={{ urlencode('Bonjour, je souhaite obtenir des informations sur vos produits et services.') }}" 
               target="_blank"
               class="flex items-center p-3 hover:bg-gray-50 transition">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-handshake text-purple-600"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-semibold text-dark">Service Commercial</p>
                    <p class="text-xs text-gray-500">Devis et informations</p>
                </div>
                <i class="fab fa-whatsapp text-green-500 text-xl"></i>
            </a>
        </div>
    </div>
</div>

<style>
    
    #whatsappToggle:checked + label {
        transform: rotate(45deg);
        background-color: #dc2626;
    }
    
    #whatsappToggle:checked + label i {
        transform: rotate(45deg);
    }
    
    label {
        transition: all 0.3s ease;
    }
</style>