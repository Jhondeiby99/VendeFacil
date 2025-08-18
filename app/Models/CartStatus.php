<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relación con los carritos
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
