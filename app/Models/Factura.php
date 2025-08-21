<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'user_id', 'total', 'detalle'
    ];

    protected $casts = [
        'detalle' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
