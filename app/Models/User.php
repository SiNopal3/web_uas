<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'last_login_at',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['is_online'];

    /**
     * Cek apakah pengguna sedang online (login dan memiliki sesi aktif dalam 15 menit terakhir).
     */
    public function getIsOnlineAttribute(): bool
    {
        if (auth()->check() && auth()->id() == $this->id) {
            return true;
        }

        return \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
            ->exists();
    }

    /**
     * Relasi ke daftar pantauan favorit (watchlists).
     */
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Relasi ke riwayat notifikasi (notifications).
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi many-to-many ke Role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relasi opsional ke primary Role (jika menggunakan role_id tunggal).
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke jejak audit (audit_logs).
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Memeriksa apakah pengguna memiliki peran tertentu.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if ($this->role && in_array($this->role->name, $roles)) {
            return true;
        }

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Memeriksa apakah pengguna memiliki hak akses (permission) tertentu.
     */
    public function hasPermission(string $permissionName): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->role && $this->role->permissions()->where('name', $permissionName)->exists()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    /**
     * Memeriksa apakah pengguna adalah Administrator (atau akun admin default).
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['Administrator', 'Admin']) ||
               $this->role_id === 1 ||
               $this->email === 'admin@gmail.com' ||
               str_contains(strtolower((string) $this->email), 'admin');
    }
}
