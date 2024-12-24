<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherFavoriteFolderRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'other_favorite_id', 'other_folder_id',
    ];

    public function other_favorite()
    {
        return $this->belongsTo(OtherFavorite::class);
    }

    public function other_folder()
    {
        return $this->belongsTo(OtherFolder::class);
    }
}
