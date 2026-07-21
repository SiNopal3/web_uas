<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'currency',
        'region',
    ];

    /**
     * Relasi ke riwayat skor risiko.
     */
    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    /**
     * Relasi ke daftar pantauan favorit (watchlists).
     */
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
}