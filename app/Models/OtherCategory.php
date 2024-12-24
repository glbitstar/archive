<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherCategory extends Model
{
    use HasFactory;

    public function other_designs()
    {
        return $this->hasMany(OtherDesign::class);
    }

    public function other_major_category()
    {
        return $this->belongsTo(OtherMajorCategory::class);
    }
}
