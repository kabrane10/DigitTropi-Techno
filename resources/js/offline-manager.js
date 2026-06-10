// Gestionnaire de mode hors-ligne
class OfflineManager {
    constructor() {
        this.dbName = 'TropiTechnoDB';
        this.dbVersion = 2;
        this.db = null;
        this.token = null;
        this.init();
    }

    async init() {
        await this.openDatabase();
        await this.registerSync();
        this.setupEventListeners();
        this.setupMessageListener();
        this.checkPendingItems();
    }

    async openDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                resolve(this.db);
            };
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Store pour les producteurs hors-ligne
                if (!db.objectStoreNames.contains('offline_producteurs')) {
                    const store = db.createObjectStore('offline_producteurs', { keyPath: 'temp_id', autoIncrement: true });
                    store.createIndex('synced', 'synced');
                    store.createIndex('created_at', 'created_at');
                }
                
                // Store pour les collectes hors-ligne
                if (!db.objectStoreNames.contains('offline_collectes')) {
                    const store = db.createObjectStore('offline_collectes', { keyPath: 'temp_id', autoIncrement: true });
                    store.createIndex('synced', 'synced');
                    store.createIndex('created_at', 'created_at');
                }
                
                // Store pour les suivis hors-ligne
                if (!db.objectStoreNames.contains('offline_suivis')) {
                    const store = db.createObjectStore('offline_suivis', { keyPath: 'temp_id', autoIncrement: true });
                    store.createIndex('synced', 'synced');
                    store.createIndex('created_at', 'created_at');
                }
            };
        });
    }

    async saveOffline(storeName, data) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.add({ 
                ...data, 
                synced: false, 
                created_at: new Date().toISOString(),
                temp_id: Date.now() + Math.random()
            });
            
            request.onsuccess = () => {
                this.checkPendingItems();
                resolve(request.result);
            };
            request.onerror = () => reject(request.error);
        });
    }

    async getOfflineData(storeName) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const index = store.index('synced');
            const request = index.getAll(IDBKeyRange.only(false));
            
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    async markAsSynced(storeName, id) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const getRequest = store.get(id);
            
            getRequest.onsuccess = () => {
                const data = getRequest.result;
                if (data) {
                    data.synced = true;
                    data.synced_at = new Date().toISOString();
                    store.put(data);
                    resolve();
                }
            };
            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    async syncAll() {
        if (!navigator.onLine) {
            console.log('Hors ligne, synchronisation différée');
            return;
        }

        if (!this.token) {
            this.token = localStorage.getItem('token');
            if (!this.token) return;
        }

        // Synchroniser les producteurs
        const producteurs = await this.getOfflineData('offline_producteurs');
        if (producteurs.length > 0) {
            await this.syncCollection('/api/producteurs/sync/batch', producteurs, 'producteurs');
        }
        
        // Synchroniser les collectes
        const collectes = await this.getOfflineData('offline_collectes');
        if (collectes.length > 0) {
            await this.syncCollection('/api/collectes/sync/batch', collectes, 'collectes');
        }
        
        // Synchroniser les suivis
        const suivis = await this.getOfflineData('offline_suivis');
        if (suivis.length > 0) {
            await this.syncCollection('/api/suivis/sync/batch', suivis, 'suivis');
        }
        
        this.checkPendingItems();
    }

    async syncCollection(url, items, type) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: JSON.stringify({ [type]: items })
            });
            
            if (response.ok) {
                const result = await response.json();
                for (const item of result.results) {
                    if (item.status === 'created') {
                        await this.markAsSynced(`offline_${type}`, item.temp_id);
                    }
                }
                this.showNotification('Synchronisation', `${items.length} élément(s) synchronisé(s)`);
            }
        } catch (error) {
            console.error(`Erreur synchronisation ${url}:`, error);
        }
    }

    async checkPendingItems() {
        const producteurs = await this.getOfflineData('offline_producteurs');
        const collectes = await this.getOfflineData('offline_collectes');
        const suivis = await this.getOfflineData('offline_suivis');
        const total = producteurs.length + collectes.length + suivis.length;
        
        // Mettre à jour l'affichage du badge
        const badge = document.getElementById('offline-badge');
        if (badge) {
            if (total > 0) {
                badge.style.display = 'inline-flex';
                badge.textContent = total;
            } else {
                badge.style.display = 'none';
            }
        }
        
        return total;
    }

    registerSync() {
        // Synchronisation automatique quand la connexion revient
        window.addEventListener('online', () => {
            this.showNotification('Connexion rétablie', 'Synchronisation en cours...');
            this.syncAll();
        });

        // Synchronisation périodique
        setInterval(() => {
            if (navigator.onLine) {
                this.syncAll();
            }
        }, 300000); // 5 minutes
        
        // Enregistrer le sync périodique avec Service Worker
        if ('serviceWorker' in navigator && 'SyncManager' in window) {
            navigator.serviceWorker.ready.then(registration => {
                registration.sync.register('sync-data');
            });
        }
    }

    setupEventListeners() {
        // Afficher le statut de connexion
        const updateOnlineStatus = () => {
            const status = document.getElementById('online-status');
            if (status) {
                if (navigator.onLine) {
                    status.innerHTML = '<i class="fas fa-wifi"></i> En ligne';
                    status.className = 'text-green-600 text-sm bg-green-100 px-3 py-1 rounded-full';
                } else {
                    status.innerHTML = '<i class="fas fa-wifi-slash"></i> Hors ligne';
                    status.className = 'text-orange-600 text-sm bg-orange-100 px-3 py-1 rounded-full';
                }
            }
        };

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();
        
        // Capturer les formulaires pour sauvegarde hors-ligne
        document.addEventListener('submit', async (e) => {
            if (!navigator.onLine && e.target.classList.contains('offline-form')) {
                e.preventDefault();
                const formData = new FormData(e.target);
                const data = Object.fromEntries(formData.entries());
                
                let storeName = 'offline_producteurs';
                if (e.target.action.includes('collectes')) storeName = 'offline_collectes';
                if (e.target.action.includes('suivi')) storeName = 'offline_suivis';
                
                data.agent_id = document.querySelector('meta[name="agent-id"]')?.content;
                
                await this.saveOffline(storeName, data);
                
                alert('✅ Données sauvegardées localement. Elles seront synchronisées automatiquement.');
                e.target.reset();
            }
        });
    }

    setupMessageListener() {
        navigator.serviceWorker.addEventListener('message', (event) => {
            if (event.data.type === 'GET_TOKEN') {
                event.ports[0].postMessage({ token: this.token });
            }
        });
    }

    showNotification(title, body) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, { body, icon: '/images/logo.png' });
        }
    }

    async requestNotificationPermission() {
        if ('Notification' in window) {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                console.log('Notifications autorisées');
            }
        }
    }

    setToken(token) {
        this.token = token;
        localStorage.setItem('token', token);
    }
}

// Initialiser le gestionnaire
const offlineManager = new OfflineManager();

// Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('Service Worker enregistré');
            offlineManager.requestNotificationPermission();
        })
        .catch(error => {
            console.error('Erreur Service Worker:', error);
        });
}