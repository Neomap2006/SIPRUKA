<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get recent notifications and unread count for current logged-in user
     */
    public function fetch()
    {
        $userId = auth()->id();
        
        $unreadCount = Notification::where('user_id', $userId)
            ->unread()
            ->count();

         $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get()
            ->map(function ($n) {
                $link = $n->link;
                if ($link) {
                    $path = parse_url($link, PHP_URL_PATH);
                    $query = parse_url($link, PHP_URL_QUERY);
                    $link = $path ? ($path . ($query ? '?' . $query : '')) : $link;
                }
                return [
                    'id'          => $n->id,
                    'title'       => $n->title,
                    'message'     => $n->message,
                    'type'        => $n->type,
                    'link'        => $link,
                    'is_read'     => (bool) $n->is_read,
                    'time_ago'    => Carbon::parse($n->created_at)->diffForHumans(),
                    'created_at'  => $n->created_at->format('d M Y H:i'),
                ];
            });
            
        return response()->json([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }
}
