<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherFavorite extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function otherDesign()
    {
        return $this->belongsTo(OtherDesign::class);
    }

    public function other_folders()
    {
        return $this->belongsToMany(OtherFolder::class, 'other_favorite_folder_relationships', 'other_favorite_id', 'other_folder_id');
    }
}
