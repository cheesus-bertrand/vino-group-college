<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vin extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku',
        'name',
        'price',
        'country',
        'region',
        'grape',
        'alcohol',
        'sugar',
        'producer',
        'litre',
        'millesime',
        'image',
        'couleur'
    ];
}
