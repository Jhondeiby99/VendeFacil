<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\CartService;

class SyncCartAfterLogin
{
    public function __construct(protected CartService $cart) {}

    public function handle(Login $event): void
    {
        // Fusiona el carrito de sesión con el del usuario autenticado
        $this->cart->mergeSessionCartToUser($event->user);

        // (opcional) mostrar aviso en la UI
        session()->flash('message', 'Carrito sincronizado con tu cuenta.');
    }
}
