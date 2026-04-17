@extends('layouts.admin')

@section('title', 'Notifications')
@section('header', 'Gestion des notifications')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-semibold text-dark">Toutes les notifications</h2>
            <p class="text-sm text-gray-500 mt-1">Gérez vos notifications, marquez-les comme lues ou supprimez-les</p>
        </div>
        <div class="flex space-x-3">
            <button id="markAllVisibleBtn" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-check-double mr-2"></i>Tout marquer comme lu
            </button>
            <button id="deleteAllBtn" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-trash-alt mr-2"></i>Tout supprimer
            </button>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <button class="filter-btn px-4 py-2 rounded-lg bg-primary text-white" data-filter="all">
                <i class="fas fa-bell mr-2"></i>Toutes
            </button>
            <button class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition" data-filter="unread">
                <i class="fas fa-envelope mr-2"></i>Non lues
            </button>
            <button class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition" data-filter="read">
                <i class="fas fa-envelope-open mr-2"></i>Lues
            </button>
        </div>
    </div>
    
    <!-- Liste des notifications -->
    <div id="notificationsList" class="space-y-3">
        <div class="text-center py-12">
            <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
            <p class="text-gray-500 mt-2">Chargement des notifications...</p>
        </div>
    </div>
    
    <!-- Pagination -->
    <div id="pagination" class="mt-6 flex justify-center"></div>
</div>

<script>
    let currentPage = 1;
    let currentFilter = 'all';
    let totalPages = 1;
    
    function loadNotifications() {
        const container = document.getElementById('notificationsList');
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                <p class="text-gray-500 mt-2">Chargement des notifications...</p>
            </div>
        `;
        
         fetch(`/admin/notifications?page=${currentPage}&filter=${currentFilter}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
           })
           .then(response => {
               if (!response.ok) throw new Error('Erreur serveur');
               return response.json();
           })
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    renderNotifications(data.notifications);
                    renderPagination(data);
                } else {
                    container.innerHTML = `
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucune notification</h3>
                            <p class="text-gray-500">Vous n'avez aucune notification pour le moment</p>
                        </div>
                    `;
                    document.getElementById('pagination').innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                container.innerHTML = `
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <i class="fas fa-exclamation-triangle text-5xl text-red-300 mb-3"></i>
                        <p class="text-gray-500">Erreur de chargement</p>
                        <p class="text-sm text-gray-400 mt-2">Veuillez réessayer plus tard</p>
                        <button onclick="loadNotifications()" class="mt-4 bg-primary text-white px-4 py-2 rounded-lg">Réessayer</button>
                    </div>
                `;
            });
    }
    
    function renderNotifications(notifications) {
        const container = document.getElementById('notificationsList');
        
        let html = '';
        notifications.forEach(notif => {
            let date = new Date(notif.created_at);
            let dateStr = date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'});
            
            html += `
                <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-all duration-300 ${!notif.is_read ? 'border-l-4 border-primary bg-blue-50/30' : ''}" data-id="${notif.id}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 cursor-pointer" onclick="window.location.href='${notif.url}'">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center ${getIconBgClass(notif.type)}">
                                    <i class="${getIconClass(notif.type)}"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-dark">${notif.title}</h3>
                                    <p class="text-xs text-gray-400">${dateStr}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 ml-13">${notif.message}</p>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            ${!notif.is_read ? `
                                <button onclick="markAsRead(${notif.id})" class="text-green-600 hover:text-green-800 transition" title="Marquer comme lu">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </button>
                            ` : ''}
                            <button onclick="deleteNotification(${notif.id})" class="text-red-500 hover:text-red-700 transition" title="Supprimer">
                                <i class="fas fa-trash-alt text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }
    
    function renderPagination(data) {
        const container = document.getElementById('pagination');
        totalPages = data.total_pages;
        
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<div class="flex space-x-2">';
        if (currentPage > 1) {
            html += `<button onclick="goToPage(${currentPage - 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-50">«</button>`;
        }
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded-lg ${i === currentPage ? 'bg-primary text-white' : 'hover:bg-gray-50'}">${i}</button>`;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += `<span class="px-3 py-1">...</span>`;
            }
        }
        if (currentPage < totalPages) {
            html += `<button onclick="goToPage(${currentPage + 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-50">»</button>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }
    
    function goToPage(page) {
        currentPage = page;
        loadNotifications();
    }
    
    function markAsRead(id) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Erreur:', error));
    }
    
    function deleteNotification(id) {
        if (!confirm('Supprimer cette notification ?')) return;
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Erreur:', error));
    }
    
    document.getElementById('markAllVisibleBtn')?.addEventListener('click', () => {
        if (!confirm('Marquer toutes les notifications comme lues ?')) return;
        fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Erreur:', error));
    });
    
    document.getElementById('deleteAllBtn')?.addEventListener('click', () => {
        if (!confirm('⚠️ Supprimer toutes les notifications ?')) return;
        fetch('/admin/notifications/delete-all', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Erreur:', error));
    });
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-primary', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.add('bg-primary', 'text-white');
            currentFilter = this.dataset.filter;
            currentPage = 1;
            loadNotifications();
        });
    });
    
    function getIconClass(type) {
        const icons = {
            'stock': 'fas fa-boxes text-orange-500',
            'credit': 'fas fa-hand-holding-usd text-red-500',
            'producteur': 'fas fa-user-plus text-green-500',
            'collecte': 'fas fa-truck-loading text-blue-500',
            'remboursement': 'fas fa-money-bill-wave text-green-500'
        };
        return icons[type] || 'fas fa-bell text-gray-500';
    }
    
    function getIconBgClass(type) {
        const classes = {
            'stock': 'bg-orange-100',
            'credit': 'bg-red-100',
            'producteur': 'bg-green-100',
            'collecte': 'bg-blue-100',
            'remboursement': 'bg-green-100'
        };
        return classes[type] || 'bg-gray-100';
    }
    
    loadNotifications();
</script>
@endsection