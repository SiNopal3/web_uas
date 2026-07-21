<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'weather_risk',
        'inflation_risk',
        'exchange_rate_risk',
        'news_sentiment_risk',
        'total_risk',
    ];

    /**
     * Relasi ke Country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
