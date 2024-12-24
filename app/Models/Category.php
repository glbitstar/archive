<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function landing_pages()
    {
        return $this->hasMany(LandingPage::class);
    }

    public function major_category()
    {
        return $this->belongsTo(MajorCategory::class, 'major_category_id');
    }
}
