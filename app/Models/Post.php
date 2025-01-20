<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'author_id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
