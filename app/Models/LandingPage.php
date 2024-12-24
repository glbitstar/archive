<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LandingPage extends Model
{
    use HasFactory, Sortable;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'landing_page_id', 'user_id')->withTimestamps();
    }
}
