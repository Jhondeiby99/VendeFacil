<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Services\CartService;
use App\Models\Product;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    protected $cartService;
    public $cartItems = [];
    public $cartTotal = 0;

    public function mount(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->cartItems = $this->cartService->items();
        $this->cartTotal = $this->cartService->total();
    }
    protected function getCartService(): CartService
    {
        return app(CartService::class);
    }

    public function procesarPago()
{
    $cartService = $this->getCartService();
    $cartItems   = $cartService->items();

    if ($cartItems->count() === 0) {
        session()->flash('error', 'Tu carrito está vacío.');
        return;
    }

    foreach ($cartItems as $item) {
        $producto = Product::find($item->id);
        if ($producto) {
            $producto->stock -= $item->cart_quantity;
            $producto->save();
        }
    }

    $factura = Factura::create([
        'user_id' => Auth::id(),
        'total'   => $cartItems->sum(fn($item) => $item->cart_price * $item->cart_quantity),
        'detalle' => $cartItems->map(fn($item) => [
            'nombre'   => $item->name,
            'cantidad' => $item->cart_quantity,
            'precio'   => $item->cart_price,
        ])->values()->toArray(),
    ]);


    $cartService->clear();
    $this->cartItems = [];
    $this->cartTotal = 0;

    // 👇 ahora es el mismo nombre que en la vista
    $this->dispatch('pago-realizado', idFactura: $factura->id);
}



    public function render()
    {
        return view('livewire.shop.checkout');
    }
}
