<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications pour le dropdown
     */
    public function getRecent()
{
    $adminId = Auth::guard('admin')->id();
    
    $notifications = Notification::where('user_id', $adminId)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    $nonLues = Notification::where('user_id', $adminId)
        ->where('is_read', false)
        ->count();
    
    return response()->json([
        'notifications' => $notifications,
        'non_lues' => $nonLues
    ]);
}
    
    /**
     * Liste paginée des notifications
     */
    public function index(Request $request)
{
    $adminId = Auth::guard('admin')->id();
    
    $query = Notification::where('user_id', $adminId)
        ->orderBy('created_at', 'desc');
    
    // Filtre
    if ($request->filter == 'unread') {
        $query->where('is_read', false);
    } elseif ($request->filter == 'read') {
        $query->where('is_read', true);
    }
    
    $notifications = $query->paginate(15);
    
    // Pour les requêtes AJAX, retourner JSON
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'notifications' => $notifications->items(),
            'current_page' => $notifications->currentPage(),
            'total_pages' => $notifications->lastPage(),
            'total' => $notifications->total()
        ]);
    }
    
    // Pour les requêtes normales, retourner la vue
    return view('admin.notifications.index');
}
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $adminId = Auth::guard('admin')->id();
        
        Notification::where(function($q) use ($adminId) {
                $q->where('user_id', $adminId)
                  ->orWhereNull('user_id');
            })
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
 * Supprimer une notification
 */
public function destroy($id)
{
    try {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Notification supprimée']);
        }
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification supprimée avec succès');
    } catch (\Exception $e) {
        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
        return back()->with('error', 'Erreur lors de la suppression');
    }
}
}