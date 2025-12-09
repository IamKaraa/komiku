<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'birth_date',
        'bio',
        'avatar',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function followedComics()
    {
        return $this->belongsToMany(Comic::class, 'comic_follows');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function purchasedComics()
    {
        return $this->belongsToMany(Comic::class, 'purchases')
            ->wherePivot('status', 'success');
    }

    // Helper methods
    public function hasPurchased(Comic $comic): bool
    {
        return $this->purchases()
            ->where('comic_id', $comic->id)
            ->where('status', 'success')
            ->exists();
    }

    public function canAccessComic(Comic $comic): bool
    {
        return !$comic->isPaid() || $this->hasPurchased($comic) || $comic->user_id === $this->id;
    }
}
