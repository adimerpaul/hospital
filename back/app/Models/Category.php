<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'photo', 'color', 'hospital_id'];
    public function hospital(){return $this->belongsTo(Hospital::class);}
    public function products(){return $this->hasMany(Product::class)->with('lots');}
}
