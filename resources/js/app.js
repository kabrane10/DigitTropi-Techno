import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Menu mobile toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            // Changer l'icône du menu
            if (mobileMenuIcon) {
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenuIcon.classList.remove('fa-times');
                    mobileMenuIcon.classList.add('fa-bars');
                } else {
                    mobileMenuIcon.classList.remove('fa-bars');
                    mobileMenuIcon.classList.add('fa-times');
                }
            }
        });
        
        // Fermer le menu mobile quand on clique sur un lien
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                if (mobileMenuIcon) {
                    mobileMenuIcon.classList.remove('fa-times');
                    mobileMenuIcon.classList.add('fa-bars');
                }
            });
        });
    }

    // Enregistrement du Service Worker pour le mode hors-ligne
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('✅ ServiceWorker enregistré avec succès:', registration.scope);
                    
                    // Vérifier les mises à jour
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        if (newWorker) {
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // Nouvelle version disponible
                                    showUpdateNotification();
                                }
                            });
                        }
                    });
                })
                .catch(error => {
                    console.error('❌ Erreur ServiceWorker:', error);
                });
        });
    }
    
    // Vérifier la connexion Internet
    function updateOnlineStatus() {
        const statusElements = document.querySelectorAll('#online-status');
        const warningElements = document.querySelectorAll('#offline-warning');
        
        if (navigator.onLine) {
            statusElements.forEach(el => {
                if (el) {
                    el.innerHTML = '<i class="fas fa-wifi"></i> En ligne';
                    el.className = 'text-sm px-3 py-1 rounded-full bg-green-100 text-green-800';
                }
            });
            warningElements.forEach(el => {
                if (el) el.classList.add('hidden');
            });
        } else {
            statusElements.forEach(el => {
                if (el) {
                    el.innerHTML = '<i class="fas fa-wifi-slash"></i> Hors ligne';
                    el.className = 'text-sm px-3 py-1 rounded-full bg-orange-100 text-orange-800';
                }
            });
            warningElements.forEach(el => {
                if (el) el.classList.remove('hidden');
            });
        }
    }
    
    function showUpdateNotification() {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-primary text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-3 animate-fade-in-up';
        toast.innerHTML = `
            <i class="fas fa-sync-alt"></i>
            <span>Nouvelle version disponible. Rafraîchissez la page.</span>
            <button onclick="location.reload()" class="ml-3 bg-white/20 px-3 py-1 rounded-lg hover:bg-white/30 transition">
                Rafraîchir
            </button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 8000);
    }
    
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();
    
    // Marquer les formulaires pour sauvegarde hors-ligne
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (!form.classList.contains('delete-confirm') && 
            !form.action.includes('logout') &&
            !form.action.includes('login')) {
            form.classList.add('offline-form');
        }
    });
});

// Styles d'animation pour les toasts
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out;
    }
`;
document.head.appendChild(style);