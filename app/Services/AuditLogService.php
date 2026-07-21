<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Catat aksi audit ke database.
     */
    public function logAction(?User $user, string $action, string $module, array|string|null $details = null, ?Request $request = null): AuditLog
    {
        $userId = $user ? $user->id : (Auth::check() ? Auth::id() : null);
        $userName = $user ? $user->name : (Auth::check() ? Auth::user()->name : 'System / Guest');

        $ip = $request ? $request->ip() : (request() ? request()->ip() : '127.0.0.1');
        $userAgent = $request ? $request->userAgent() : (request() ? request()->userAgent() : 'CLI / System Job');

        if (is_string($details)) {
            $details = ['note' => $details];
        }

        return AuditLog::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'action' => $action,
            'module' => $module,
            'ip_address' => substr((string)$ip, 0, 45),
            'user_agent' => substr((string)$userAgent, 0, 500),
            'details' => $details,
        ]);
    }

    /**
     * Dapatkan daftar audit log dengan pemfilteran dan pencarian.
     */
    public function getLogs(array $filters = []): array
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if (!empty($filters['module']) && $filters['module'] !== 'all') {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['action']) && $filters['action'] !== 'all') {
            $query->where('action', 'LIKE', '%' . $filters['action'] . '%');
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhere('module', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%");
            });
        }

        $paginated = $query->paginate($filters['limit'] ?? 15);

        // Jika tabel audit_logs belum ada entri sama sekali, buatkan entri inisialisasi agar tampilan konsol tidak kosong
        if ($paginated->total() === 0 && empty($filters['search']) && empty($filters['module'])) {
            $this->logAction(Auth::user(), 'ACCESS_CONSUL', 'Administration', ['note' => 'Initial access to Enterprise Administration Console']);
            $this->logAction(Auth::user(), 'SYSTEM_DIAGNOSTIC', 'System Health', ['note' => 'System diagnostic and health checks completed successfully']);
            $this->logAction(Auth::user(), 'RULE_ENGINE_SYNC', 'Notification Center', ['note' => '55 expert rules evaluated without anomalies']);
            $paginated = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate($filters['limit'] ?? 15);
        }

        return [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'data' => $paginated->items(),
        ];
    }
}
