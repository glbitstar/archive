<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherMajorCategory extends Model
{
    use HasFactory;

    public function other_categories()
    {
        return $this->hasMany(OtherDesign::class);
    }
}
