<!-- Bannière d'installation PWA -->
<div id="installBanner" class="hidden fixed bottom-0 left-0 right-0 bg-gradient-to-r from-primary to-secondary text-white p-4 shadow-lg z-50 transform transition-transform duration-300 translate-y-full">
    <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-3">
            <div class="bg-white/20 p-2 rounded-lg">
                <i class="fas fa-mobile-alt text-2xl"></i>
            </div>
            <div>
                <p class="font-semibold">Installez l'application Tropi-Techno</p>
                <p class="text-sm text-white/80">Travaillez hors ligne et synchronisez automatiquement vos données</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button id="closeInstallBanner" class="px-4 py-2 rounded-lg border border-white/30 hover:bg-white/10 transition">
                Plus tard
            </button>
            <button id="confirmInstallBanner" class="px-4 py-2 rounded-lg bg-white text-primary font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-download mr-2"></i>Installer
            </button>
        </div>
    </div>
</div>

<script>
    let deferredInstallPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredInstallPrompt = e;
        
        const banner = document.getElementById('installBanner');
        if (banner) {
            banner.classList.remove('hidden');
            setTimeout(() => {
                banner.classList.remove('translate-y-full');
            }, 100);
        }
    });
    
    document.getElementById('confirmInstallBanner')?.addEventListener('click', async () => {
        if (deferredInstallPrompt) {
            deferredInstallPrompt.prompt();
            const { outcome } = await deferredInstallPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('Application installée');
            }
            deferredInstallPrompt = null;
            
            const banner = document.getElementById('installBanner');
            banner.classList.add('translate-y-full');
            setTimeout(() => banner.classList.add('hidden'), 300);
        }
    });
    
    document.getElementById('closeInstallBanner')?.addEventListener('click', () => {
        const banner = document.getElementById('installBanner');
        banner.classList.add('translate-y-full');
        setTimeout(() => banner.classList.add('hidden'), 300);
    });
</script>