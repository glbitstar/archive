<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteFolderRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'favorite_id', 'folder_id',
    ];

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
