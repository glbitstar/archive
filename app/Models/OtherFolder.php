<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherFolder extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function otherFavorite()
    {
        return $this->hasMany(OtherFavorite::class, 'other_favorite_folder_relationships', 'other_folder_id', 'other_favorite_id');
    }
}
