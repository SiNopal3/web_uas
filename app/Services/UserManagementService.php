<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementService
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
        $this->ensureDefaultRolesAndPermissions();
    }

    /**
     * Pastikan role standar (Admin dan User) sudah tersedia dan bersihkan role lama.
     */
    public function ensureDefaultRolesAndPermissions(): void
    {
        $roles = [
            'Admin' => 'Enterprise System Administrator with full access to all modules and configurations',
            'User' => 'General User with access to risk monitoring and analysis features',
        ];

        foreach ($roles as $name => $desc) {
            Role::firstOrCreate(['name' => $name], ['description' => $desc]);
        }

        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        // Migrasi & hapus role lama agar hanya tersisa 2 role: Admin dan User
        $oldAdminRole = Role::where('name', 'Administrator')->first();
        if ($oldAdminRole && $adminRole) {
            User::where('role_id', $oldAdminRole->id)->update(['role_id' => $adminRole->id]);
            foreach ($oldAdminRole->users as $u) {
                $u->roles()->sync([$adminRole->id]);
            }
        }

        if ($userRole && $adminRole) {
            // Pastikan semua user admin maupun user reguler ter-assign role_id dengan benar (termasuk yang null)
            $allUsers = User::all();
            foreach ($allUsers as $u) {
                if ($u->isAdmin()) {
                    if ($u->role_id !== $adminRole->id) {
                        $u->update(['role_id' => $adminRole->id]);
                    }
                    if (!$u->roles()->where('roles.id', $adminRole->id)->exists()) {
                        $u->roles()->sync([$adminRole->id]);
                    }
                } else {
                    if ($u->role_id !== $userRole->id || $u->role_id === null) {
                        $u->update(['role_id' => $userRole->id]);
                    }
                    if (!$u->roles()->where('roles.id', $userRole->id)->exists()) {
                        $u->roles()->sync([$userRole->id]);
                    }
                }
            }

            $oldRoles = Role::whereNotIn('name', ['Admin', 'User'])->get();
            foreach ($oldRoles as $oldRole) {
                $oldRole->delete();
            }
        }

        $permissions = [
            'manage_users' => 'Create, update, delete users and assign roles',
            'manage_system' => 'Configure system settings, thresholds, and refresh rates',
            'view_audit_logs' => 'Inspect and export system audit logs',
            'run_simulations' => 'Execute scenario simulations and AI decision support',
            'view_dashboards' => 'Access live, executive, and prediction dashboards',
        ];

        foreach ($permissions as $name => $desc) {
            Permission::firstOrCreate(['name' => $name], ['description' => $desc]);
        }
    }

    /**
     * Dapatkan daftar pengguna dengan paginasi, pencarian debounced, dan filter status/role/sort.
     */
    public function getUsersList(array $filters = []): array
    {
        $query = User::with('roles', 'role');

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'name' || $sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $threshold = now()->subMinutes(15)->getTimestamp();
            if ($filters['status'] === 'online' || $filters['status'] === 'active') {
                $query->where(function ($q) use ($threshold) {
                    $q->whereIn('id', function($sq) use ($threshold) {
                        $sq->select('user_id')->from('sessions')
                          ->whereNotNull('user_id')
                          ->where('last_activity', '>=', $threshold);
                    });
                    if (auth()->check()) {
                        $q->orWhere('id', auth()->id());
                    }
                });
            } elseif ($filters['status'] === 'offline' || $filters['status'] === 'inactive' || $filters['status'] === 'suspended') {
                $query->whereNotIn('id', function($q) use ($threshold) {
                    $q->select('user_id')->from('sessions')
                      ->whereNotNull('user_id')
                      ->where('last_activity', '>=', $threshold);
                });
                if (auth()->check()) {
                    $query->where('id', '!=', auth()->id());
                }
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if (!empty($filters['role']) && $filters['role'] !== 'all') {
            $roleName = $filters['role'];
            $query->where(function ($q) use ($roleName) {
                $q->whereHas('roles', fn($r) => $r->where('name', $roleName))
                  ->orWhereHas('role', fn($r) => $r->where('name', $roleName));
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        $paginated = $query->paginate($filters['limit'] ?? 10);

        return [
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'data' => $paginated->items(),
            'available_roles' => Role::all(),
            'kpi' => $this->getUserSummaryKpi(),
        ];
    }

    /**
     * Buat pengguna baru.
     */
    public function createUser(array $data, ?User $creator = null): User
    {
        return DB::transaction(function () use ($data, $creator) {
            $roleId = $data['role_id'] ?? null;
            if (!$roleId && !empty($data['role'])) {
                $roleObj = Role::where('name', $data['role'])->first();
                $roleId = $roleObj ? $roleObj->id : null;
            }
            if (!$roleId) {
                $defaultRoleName = (str_contains(strtolower($data['email']), 'admin') || ($data['role'] ?? '') === 'Admin') ? 'Admin' : 'User';
                $roleObj = Role::where('name', $defaultRoleName)->first() ?: Role::firstOrCreate(['name' => $defaultRoleName]);
                $roleId = $roleObj->id;
            }

            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password123'),
                'status' => $data['status'] ?? 'active',
                'role_id' => $roleId,
            ]);

            if ($roleId) {
                $user->roles()->sync([$roleId]);
            }

            $this->auditLogService->logAction($creator, 'CREATE_USER', 'User Management', [
                'created_user_id' => $user->id,
                'created_user_email' => $user->email,
                'role_assigned' => $data['role'] ?? ($user->role->name ?? 'User')
            ]);

            return $user;
        });
    }

    /**
     * Perbarui data pengguna dan hak akses peran.
     */
    public function updateUser(int $userId, array $data, ?User $editor = null): User
    {
        return DB::transaction(function () use ($userId, $data, $editor) {
            $user = User::findOrFail($userId);

            $updateFields = [];
            if (isset($data['name'])) $updateFields['name'] = $data['name'];
            if (isset($data['username'])) $updateFields['username'] = $data['username'];
            if (isset($data['email'])) $updateFields['email'] = $data['email'];
            if (isset($data['status'])) $updateFields['status'] = $data['status'];
            if (!empty($data['password'])) {
                $updateFields['password'] = Hash::make($data['password']);
            }

            if (isset($data['role_id'])) {
                $updateFields['role_id'] = $data['role_id'];
                $user->roles()->sync([$data['role_id']]);
            } elseif (!empty($data['role'])) {
                $role = Role::where('name', $data['role'])->first();
                if ($role) {
                    $updateFields['role_id'] = $role->id;
                    $user->roles()->sync([$role->id]);
                }
            }

            $user->update($updateFields);

            $this->auditLogService->logAction($editor, 'UPDATE_USER', 'User Management', [
                'updated_user_id' => $user->id,
                'updated_user_email' => $user->email,
                'fields' => array_keys($updateFields),
            ]);

            return $user;
        });
    }

    /**
     * Hapus pengguna dari sistem.
     */
    public function deleteUser(int $userId, ?User $deleter = null): bool
    {
        return DB::transaction(function () use ($userId, $deleter) {
            $user = User::findOrFail($userId);
            $email = $user->email;

            $user->roles()->detach();
            $deleted = $user->delete();

            if ($deleted) {
                $this->auditLogService->logAction($deleter, 'DELETE_USER', 'User Management', [
                    'deleted_user_id' => $userId,
                    'deleted_user_email' => $email,
                ]);
            }

            return (bool)$deleted;
        });
    }

    /**
     * Dapatkan ringkasan KPI untuk dasbor Enterprise User Management.
     */
    public function getUserSummaryKpi(): array
    {
        $threshold = now()->subMinutes(15)->getTimestamp();
        $onlineUserIds = \Illuminate\Support\Facades\DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $threshold)
            ->pluck('user_id')
            ->toArray();

        if (auth()->check() && !in_array(auth()->id(), $onlineUserIds)) {
            $onlineUserIds[] = auth()->id();
        }

        $onlineCount = count(array_unique($onlineUserIds));
        $totalCount = User::count();

        $adminCount = User::where(function($q) {
            $q->whereHas('role', fn($r) => $r->whereIn('name', ['Admin', 'Administrator']))
              ->orWhereHas('roles', fn($r) => $r->whereIn('name', ['Admin', 'Administrator']))
              ->orWhere('role_id', 1)
              ->orWhere('email', 'admin@gmail.com')
              ->orWhere('email', 'LIKE', '%admin%');
        })->count();

        $userCount = max(0, $totalCount - $adminCount);

        return [
            'total_users' => $totalCount,
            'active_users' => $onlineCount,
            'inactive_users' => max(0, $totalCount - $onlineCount),
            'role_administrator' => $adminCount,
            'role_analyst' => 0,
            'role_user' => $userCount,
            'role_viewer' => 0,
        ];
    }

    /**
     * Dapatkan detail satu pengguna untuk modal detail.
     */
    public function getUserDetail(int $userId): User
    {
        return User::with('roles', 'role')->findOrFail($userId);
    }

    /**
     * Perbarui status pengguna (active, inactive, suspended).
     */
    public function updateUserStatus(int $userId, string $status, ?User $editor = null): User
    {
        return $this->updateUser($userId, ['status' => $status], $editor);
    }
}
