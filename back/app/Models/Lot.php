<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;
    protected $fillable = [
        'hospital_id',
        'product_id',
        'quantity',
        'priceCost',
        'priceSale',
        'expiration',
        'batch',
    ];
}
