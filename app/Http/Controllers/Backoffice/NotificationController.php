<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = DB::table('notifications')
                ->leftJoin('commandes', 'notifications.commande_id', '=', 'commandes.id')
                ->leftJoin('tables_restaurant', 'commandes.table_id', '=', 'tables_restaurant.id')
                ->select(
                    'notifications.id',
                    'notifications.message',
                    'notifications.commande_id',
                    'notifications.lu',
                    'notifications.created_at as notification_date',
                    'commandes.created_at as commande_date',
                    'tables_restaurant.numero as table_numero'
                )
                ->orderBy('notifications.created_at', 'desc')
                ->get()
                ->map(function ($notif) {
                    $notif->commande_date = $notif->commande_date
                        ? date('Y-m-d H:i:s', strtotime($notif->commande_date))
                        : null;
                    return $notif;
                });

            return response()->json($notifications);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur récupération notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            DB::table('notifications')->where('id', $id)->update(['lu' => 1]);
            return response()->json(['message' => 'Notification marquée comme lue']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
