<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Permitir estos campos para asignación masiva
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];
}
