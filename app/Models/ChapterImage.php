<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'image_path',
        'order_no',
    ];

    // Relationships
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
