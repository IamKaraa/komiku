<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'comic_id',
        'title',
        'order_no',
        'views',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships
    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ChapterImage::class)->orderBy('order_no');
    }

    // Accessors
    public function getTotalImagesAttribute()
    {
        return $this->images()->count();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
