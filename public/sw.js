const CACHE_NAME = 'tropi-techno-v2'; // Cache version updated
const OFFLINE_URL = '/offline';

// Fichiers à mettre en cache
const urlsToCache = [
    '/',
    OFFLINE_URL,
    '/agent/dashboard',
    '/css/app.css',
    '/js/app.js',
    '/js/offline-manager.js',
    '/manifest.json',
    '/images/logo.png'
];

// --- Database Configuration ---
const DB_NAME = 'TropiTechnoDB';
const DB_VERSION = 2; 

// --- Helper function to open IndexedDB ---
function openDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
        // onupgradeneeded is handled by the main script (offline-manager.js)
    });
}

// --- Installation du Service Worker ---
self.addEventListener('install', event => {
    console.log('[Service Worker] Installation');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[Service Worker] Cache ajouté');
                return cache.addAll(urlsToCache);
            })
            .catch(err => console.error('[Service Worker] Erreur cache:', err))
    );
    self.skipWaiting();
});

// --- Activation du Service Worker ---
self.addEventListener('activate', event => {
    console.log('[Service Worker] Activation');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log('[Service Worker] Ancien cache supprimé:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// --- Interception des requêtes ---
self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);

    // Stratégie pour les requêtes API
    if (url.pathname.startsWith('/api/')) {
        // Pour les requêtes de synchronisation, on va directement au réseau
        if (event.request.method === 'POST') {
             event.respondWith(fetch(event.request).catch(() => {
                return new Response(
                    JSON.stringify({ error: 'La requête a échoué. Elle sera réessayée plus tard.' }),
                    { headers: { 'Content-Type': 'application/json' } }
                );
             }));
        } else { // Pour les GET, on essaie le réseau puis le cache
            event.respondWith(
                fetch(event.request)
                    .then(response => {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseClone);
                        });
                        return response;
                    })
                    .catch(() => caches.match(event.request))
            );
        }
    }
    // Stratégie pour les pages HTML (Network falling back to cache, then offline page)
    else if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => caches.match(event.request))
                .catch(() => caches.match(OFFLINE_URL))
        );
    }
    // Stratégie pour les autres requêtes (Cache First)
    else {
        event.respondWith(
            caches.match(event.request)
                .then(response => response || fetch(event.request))
        );
    }
});

// --- Synchronisation en arrière-plan ---
self.addEventListener('sync', event => {
    console.log('[Service Worker] Sync Event:', event.tag);
    if (event.tag === 'sync-data') {
        event.waitUntil(syncOfflineData());
    }
});

// --- Fonction principale de synchronisation ---
async function syncOfflineData() {
    console.log('[Service Worker] Tentative de synchronisation des données.');
    
    let db;
    try {
        db = await openDatabase();
        
        // On synchronise chaque "store"
        await syncStore(db, 'offline_producteurs', '/api/producteurs/sync/batch', 'producteurs');
        await syncStore(db, 'offline_collectes', '/api/collectes/sync/batch', 'collectes');
        await syncStore(db, 'offline_suivis', '/api/suivis/sync/batch', 'suivis');

        console.log('[Service Worker] Synchronisation terminée.');
        
    } catch (error) {
        console.error('[Service Worker] Erreur lors de la synchronisation:', error);
    } finally {
        if(db) db.close();
    }
}

// --- Logique de synchronisation pour un store spécifique ---
async function syncStore(db, storeName, apiUrl, payloadKey) {
    const items = await getPendingItems(db, storeName);
    if (items.length === 0) {
        return; // Rien à synchroniser
    }

    console.log(`[Service Worker] ${items.length} élément(s) à synchroniser pour ${storeName}.`);

    try {
        // Note: L'authentification doit être gérée par les cookies de session httpOnly 
        // ou un token qui ne nécessite pas d'interaction client.
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
                // 'Authorization': `Bearer ${token}` // Le token doit être accessible
            },
            body: JSON.stringify({ [payloadKey]: items })
        });

        if (response.ok) {
            const result = await response.json();
            console.log(`[Service Worker] Sync réussie pour ${storeName}:`, result);
            
            // Marquer les éléments comme synchronisés
            if (result.results) {
                 await markItemsAsSynced(db, storeName, result.results);
            }
             self.registration.showNotification('Synchronisation réussie', {
                body: `${items.length} ${payloadKey} ont été synchronisé(e)s.`,
                icon: '/images/logo.png'
            });

        } else {
            console.error(`[Service Worker] Erreur API pour ${storeName}:`, response.statusText);
        }
    } catch (error) {
        console.error(`[Service Worker] Erreur fetch pour ${storeName}:`, error);
        // La synchronisation sera retentée automatiquement par le navigateur
        throw error;
    }
}


// --- Fonctions utilitaires pour IndexedDB ---

function getPendingItems(db, storeName) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], 'readonly');
        const store = transaction.objectStore(storeName);
        const index = store.index('synced');
        const request = index.getAll(IDBKeyRange.only('false')); // 'false' en string si non-booléen
         request.onsuccess = (e) => {
             // IndexedDB peut stocker 'false' comme 0. On vérifie les deux.
             const allItems = e.target.result;
             const filteredItems = allItems.filter(item => item.synced === false);
             resolve(filteredItems);
         };
        request.onerror = (e) => reject(e.target.error);
    });
}

function markItemsAsSynced(db, storeName, successfulSyncs) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], 'readwrite');
        const store = transaction.objectStore(storeName);
        
        successfulSyncs.forEach(item => {
            if (item.status === 'created' || item.status === 'synced') {
                 // On supprime l'enregistrement local après synchronisation réussie
                store.delete(item.temp_id);
            }
        });
        
        transaction.oncomplete = () => {
            console.log(`[Service Worker] ${successfulSyncs.length} élément(s) marqué(s) et supprimé(s) de ${storeName}.`);
            resolve();
        };
        transaction.onerror = (e) => reject(e.target.error);
    });
}
