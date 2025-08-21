<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\Services\CartService;

class ShopDashboard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $cart = []; // ['productId' => quantity]

    protected $listeners = ['productAdded' => '$refresh', 'productRemoved' => '$refresh', 'productUpdated' => '$refresh'];

    // 👇 la hacemos protected y sin tipado
    protected $cartService;
    



    public function updatingSearch()
    {
        $this->resetPage();
    }   
    
    public function addToCart($productId, $qty = 1)
    {
        $cartService = app(CartService::class); // 👈 siempre resuelto
        [$ok, $msg] = $cartService->add($productId, $qty);
        if (!$ok) session()->flash('errorp', $msg);
        $this->cart = $cartService->getCartMap();

        $this->dispatch('notify', type: $ok ? 'success' : 'error', message: $msg);
    }

    public function removeFromCart($productId)
    {
        $cartService = app(CartService::class); // 👈 siempre resuelto
        [$ok, $msg] = $cartService->remove($productId);
        $this->cart = $cartService->getCartMap();
    }

    public function updateQuantity($productId, $qty)
    {
        $cartService = app(CartService::class); // 👈 siempre resuelto
        [$ok, $msg] = $cartService->update($productId, (int)$qty);
        if (!$ok) session()->flash('error', $msg);
        $this->cart = $cartService->getCartMap();
    }

    public function getCartItemsProperty()
    {
        return app(CartService::class)->items();
    }

    public function getCartTotalProperty()
    {
        return app(CartService::class)->total();
    }


    public function render()
    {
        $cartService = app(CartService::class);
        $this->cart = $cartService->getCartMap();
        $products = Product::query()
            ->when($this->search, function($q) {
                $q->where(function($qq){
                    $qq->where('name','like','%'.$this->search.'%')
                       ->orWhere('description','like','%'.$this->search.'%');
                })
                ->Where('stock', '>', 0);
            }
            , function ($q) {
            // Si no hay búsqueda, igual queremos productos con stock
            $q->where('stock', '>', 0);
        })
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('livewire.shop.shop-dashboard', [
            'products'  => $products,
            'cartItems' => $this->cartItems,
            'cartTotal' => $this->cartTotal,
        ]);
    }
}
