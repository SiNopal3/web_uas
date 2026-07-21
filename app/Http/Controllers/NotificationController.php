<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    /**
     * Injeksi NotificationService ke dalam Thin Controller.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * GET /notifications
     * Merender tampilan Blade utama Smart Notification Center (`notifications.index`).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['country', 'priority', 'category', 'type', 'status', 'search']);
        $data = $this->notificationService->getNotificationsData($user, $filters);

        return view('notifications.index', compact('data'));
    }

    /**
     * GET /api/notifications
     * Endpoint AJAX berkecepatan tinggi yang mengembalikan dataset JSON lengkap dan statistik.
     */
    public function getData(Request $request): JsonResponse
    {
        $user = Auth::user();
        $filters = $request->only(['country', 'priority', 'category', 'type', 'status', 'search']);
        $data = $this->notificationService->getNotificationsData($user, $filters);

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * POST /api/notifications/read/{id}
     * Tandai satu notifikasi sebagai telah dibaca (`is_read = true`).
     */
    public function markRead(int $id): JsonResponse
    {
        $userId = Auth::id();
        $success = $this->notificationService->markAsRead($id, $userId);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found or unauthorized.'
        ], 404);
    }

    /**
     * POST /api/notifications/read-all
     * Tandai seluruh notifikasi yang belum dibaca milik pengguna sebagai telah dibaca.
     */
    public function markAllRead(): JsonResponse
    {
        $userId = Auth::id();
        $count = $this->notificationService->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => "Successfully marked {$count} notifications as read.",
            'count' => $count
        ], 200);
    }

    /**
     * DELETE /api/notifications/{id}
     * Hapus satu notifikasi dari database.
     */
    public function destroy(int $id): JsonResponse
    {
        $userId = Auth::id();
        $success = $this->notificationService->deleteNotification($id, $userId);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found or unauthorized.'
        ], 404);
    }

    /**
     * DELETE /api/notifications/clear
     * Bersihkan / hapus seluruh riwayat notifikasi milik pengguna.
     */
    public function clear(): JsonResponse
    {
        $userId = Auth::id();
        $count = $this->notificationService->clearAllNotifications($userId);

        return response()->json([
            'success' => true,
            'message' => "Successfully cleared {$count} notifications from history.",
            'count' => $count
        ], 200);
    }
}
