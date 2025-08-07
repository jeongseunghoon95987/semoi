<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // 추가

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'url',
        'thumbnail_url',
        'event_source_id', // 추가
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the event source that owns the event.
     */
    public function eventSource(): BelongsTo
    {
        return $this->belongsTo(EventSource::class);
    }
}