<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $table = 'scheduled_reports';

    protected $fillable = [
        'user_id',
        'user_name',
        'report_type',
        'frequency',
        'parameters',
        'recipients',
        'next_run_at',
        'status',
    ];

    protected $casts = [
        'parameters' => 'array',
        'next_run_at' => 'datetime',
    ];

    /**
     * Get the user that owns the scheduled report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
