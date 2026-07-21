<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExportLog extends Model
{
    use HasFactory;

    protected $table = 'export_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'report_type',
        'format',
        'ip_address',
        'user_agent',
        'status',
        'execution_time_ms',
    ];

    protected $casts = [
        'execution_time_ms' => 'float',
    ];

    /**
     * Get the user associated with the export log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
