<?php

namespace App\Models;

use App\Casts\Categories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['price', 'name', 'description', 'published'];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
