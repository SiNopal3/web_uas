<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi many-to-many ke User.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relasi many-to-many ke Permission.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
