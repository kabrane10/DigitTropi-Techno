// Gestion des notifications
class NotificationManager {
    constructor() {
        this.btn = document.getElementById('notificationBtn');
        this.dropdown = document.getElementById('notificationDropdown');
        this.list = document.getElementById('notificationList');
        this.count = document.getElementById('notificationCount');
        this.markAllBtn = document.getElementById('markAllRead');
        this.interval = null;
        
        this.init();
    }
    
    init() {
        if (!this.btn) return;
        
        // Toggle dropdown
        this.btn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.dropdown.classList.toggle('hidden');
            if (!this.dropdown.classList.contains('hidden')) {
                this.loadNotifications();
            }
        });
        
        // Fermer en cliquant ailleurs
        document.addEventListener('click', (e) => {
            if (!this.btn.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.dropdown.classList.add('hidden');
            }
        });
        
        // Marquer tout comme lu
        if (this.markAllBtn) {
            this.markAllBtn.addEventListener('click', () => this.markAllAsRead());
        }
        
        // Charger les notifications au démarrage
        this.loadNotifications();
        
        // Rafraîchir toutes les 30 secondes
        this.interval = setInterval(() => this.loadNotifications(), 30000);
    }
    
    loadNotifications() {
        fetch('/admin/notifications/recent')
            .then(response => response.json())
            .then(data => {
                this.updateCount(data.non_lues);
                this.renderNotifications(data.notifications);
            })
            .catch(error => console.error('Erreur:', error));
    }
    
    updateCount(nonLues) {
        if (nonLues > 0) {
            this.count.textContent = nonLues > 99 ? '99+' : nonLues;
            this.count.classList.remove('hidden');
        } else {
            this.count.classList.add('hidden');
        }
    }
    
    renderNotifications(notifications) {
        if (!this.list) return;
        
        if (notifications.length === 0) {
            this.list.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-3xl mb-2 opacity-50"></i>
                    <p>Aucune notification</p>
                </div>
            `;
            return;
        }
        
        this.list.innerHTML = '';
        notifications.forEach(notif => {
            const div = document.createElement('div');
            div.className = `p-3 border-b hover:bg-gray-50 cursor-pointer transition ${!notif.is_read ? 'bg-blue-50' : ''}`;
            div.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        ${this.getIcon(notif.type)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">${notif.title}</p>
                        <p class="text-xs text-gray-500 truncate">${notif.message}</p>
                        <p class="text-xs text-gray-400 mt-1">${this.getTimeAgo(notif.created_at)}</p>
                    </div>
                    ${!notif.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>' : ''}
                </div>
            `;
            
            div.addEventListener('click', () => {
                if (!notif.is_read) {
                    this.markAsRead(notif.id);
                }
                if (notif.url) {
                    window.location.href = notif.url;
                }
            });
            
            this.list.appendChild(div);
        });
    }
    
    getIcon(type) {
        const icons = {
            'stock': '<i class="fas fa-boxes text-orange-500 text-lg"></i>',
            'credit': '<i class="fas fa-hand-holding-usd text-red-500 text-lg"></i>',
            'producteur': '<i class="fas fa-user-plus text-green-500 text-lg"></i>',
            'collecte': '<i class="fas fa-truck-loading text-blue-500 text-lg"></i>',
            'remboursement': '<i class="fas fa-money-bill-wave text-green-500 text-lg"></i>'
        };
        return icons[type] || '<i class="fas fa-bell text-gray-400 text-lg"></i>';
    }
    
    getTimeAgo(date) {
        const now = new Date();
        const past = new Date(date);
        const diff = Math.floor((now - past) / 1000);
        
        if (diff < 60) return 'à l\'instant';
        if (diff < 3600) return `${Math.floor(diff / 60)} min`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} h`;
        return `${Math.floor(diff / 86400)} j`;
    }
    
    markAsRead(id) {
        fetch(`/admin/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(() => this.loadNotifications())
            .catch(error => console.error('Erreur:', error));
    }
    
    markAllAsRead() {
        fetch('/admin/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(() => this.loadNotifications())
            .catch(error => console.error('Erreur:', error));
    }
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    new NotificationManager();
});