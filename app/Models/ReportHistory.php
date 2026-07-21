<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportHistory extends Model
{
    use HasFactory;

    protected $table = 'report_history';

    protected $fillable = [
        'user_id',
        'user_name',
        'report_type',
        'title',
        'file_format',
        'file_size_kb',
        'download_count',
        'parameters',
    ];

    protected $casts = [
        'parameters' => 'array',
        'file_size_kb' => 'float',
        'download_count' => 'integer',
    ];

    /**
     * Get the user that generated the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
