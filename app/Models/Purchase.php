<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comic_id',
        'order_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_type',
        'status',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_details' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper methods
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
