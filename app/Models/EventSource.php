<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // 추가

class EventSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'is_active',
        'list_selector',
        'item_selector',
        'title_selector',
        'description_selector',
        'url_selector',
        'date_selector',
        'start_date_selector',
        'end_date_selector',
        'thumbnail_selector',
        'last_crawled_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_crawled_at' => 'datetime',
    ];

    /**
     * Get the events for the event source.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}