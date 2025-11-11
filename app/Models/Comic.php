<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $table = 'comic';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'status',
        'approved_by',
        'approved_at'
    ];    

    /**
     * The genres that belong to the comic.
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'comic_genre');
    }
}
