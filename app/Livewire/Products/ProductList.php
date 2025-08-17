<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductList extends Component
{
    public $search = ''; // Para el buscador

    protected $listeners = ['productUpdated' => '$refresh']; // Refrescar si se actualiza un producto

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Reiniciar autoincrement en SQLite
        DB::statement("DELETE FROM sqlite_sequence WHERE name='products'");

        session()->flash('message', 'Producto eliminado correctamente.');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.products.product-list', compact('products'));
    }
}
