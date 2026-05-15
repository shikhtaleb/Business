<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['filename', 'original_name', 'mime_type', 'size', 'disk', 'path', 'url', 'type'];

    public function getPublicUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
