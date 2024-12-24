<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDesign extends Model
{
    use HasFactory;

    public function other_major_category()
    {
        return $this->belongsTo(OtherMajorCategory::class, 'other_major_category_id');
    }

    public function other_favorites()
    {
        return $this->belongsToMany(User::class, 'other_favorites', 'other_design_id', 'user_id')->withTimestamps();
    }
    
    public function otherFoldersThroughFavorites()
    {
        return $this->hasManyThrough(
            OtherFolder::class,
            OtherFavorite::class,
            'other_design_id', // OtherFavorite における OtherDesign への外部キー
            'id', // OtherFolder における主キー
            'id', // OtherDesign における主キー
            'other_folder_id' // OtherFavorite における OtherFolder への外部キー
        );
    }
    
}
