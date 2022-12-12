<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'hospital_id',
        'category_id',
        'barra',
        'name',
        'stock',
        'stockMin',
        'stockMax',
        'photo',
        'color',
    ];
    public function category(){return $this->belongsTo(Category::class);}
    public function hospital(){return $this->belongsTo(Hospital::class);}
    public function lots(){return $this->hasMany(Lot::class);}
}
