<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ShopDashboard extends Component
{
    
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $cart = []; // ['productId' => quantity]
    

    protected $listeners = ['productAdded' => '$refresh', 'productRemoved' => '$refresh', 'productUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }   

    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::find($productId);
        if (isset($this->cart[$productId]) && $quantity <= $product->stock) {
            if( $this->cart[$productId] + $quantity > $product->find($productId)->stock) {
                session()->flash('errorp', "Solo hay {$product->stock} unidades disponibles de {$product->name}.");
                return;
            }
            $this->cart[$productId] += $quantity;
        } else {
            $this->cart[$productId] = $quantity;
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function updateQuantity($productId, $quantity)
{
    $product = Product::find($productId);

    if (!$product) return;

    if ($quantity <= 0) {
        $this->removeFromCart($productId);
        return;
    }

    if ($quantity > $product->stock) {
        session()->flash('error', "Solo hay {$product->stock} unidades disponibles de {$product->name}.");
        $this->cart[$productId] = $product->stock; // limita al máximo permitido
        return;
    }

    $this->cart[$productId] = $quantity;
}


    public function getCartItemsProperty()
    {
        $ids = array_keys($this->cart); // extrae solo los IDs
        return Product::whereIn('id', $ids)->get();
    }


    public function getCartTotalProperty()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item->price * $this->cart[$item->id];
        }
        return $total;
    }

    

    public function render()
    {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('livewire.shop.shop-dashboard', [
            'products' => $products,
            'cartItems' => $this->cartItems,
            'cartTotal' => $this->cartTotal,
        ]);
    }


}
