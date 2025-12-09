<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comic';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'synopsis',
        'thumbnail_path',
        'is_paid',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {   
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'comic_genre');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order_no');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comic_follows');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function buyers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'purchases')
            ->wherePivot('status', 'success');
    }

    // Accessors & Mutators
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getTotalViewsAttribute()
    {
        try {
            return $this->chapters()->sum('views') ?? 0;
        } catch (\Exception $e) {
            // If the views column doesn't exist, return 0
            return 0;
        }
    }

    public function getTotalFollowersAttribute()
    {
        return $this->followers()->count();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    // Helper methods
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('user_id', $user->id)->exists();
    }

    public function getRatingBy(User $user)
    {
        return $this->ratings()->where('user_id', $user->id)->first();
    }

    public function isPaid(): bool
    {
        return $this->is_paid;
    }

    public function isFree(): bool
    {
        return !$this->is_paid;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comic) {
            if (empty($comic->slug)) {
                $comic->slug = Str::slug($comic->title);
            }
        });

        static::updating(function ($comic) {
            if ($comic->isDirty('title') && empty($comic->slug)) {
                $comic->slug = Str::slug($comic->title);
            }
        });
    }
}
