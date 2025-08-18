<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Si la tabla se llama cart_items no hace falta $table.
    // protected $table = 'cart_items';

    protected $fillable = [
        'user_id',      // nullable si el usuario no está logueado
        'session_id',   // para invitados
        'product_id',
        'quantity',
        'unit_price',   // precio en el momento de agregar
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
    ];

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
