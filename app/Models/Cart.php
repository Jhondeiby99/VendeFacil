<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    // Relación con los elementos del carrito
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el estado del carrito
    public function status()
    {
        return $this->belongsTo(CartStatus::class);
    }
}
