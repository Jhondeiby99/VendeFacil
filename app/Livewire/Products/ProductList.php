<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = ''; // Para el buscador

    protected $listeners = ['productUpdated' => '$refresh']; // Refrescar si se actualiza un producto

    public function updatingSearch()
    {
        $this->resetPage();
    }  

    public function deleteProduct($id)
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
            ->paginate(9);

        return view('livewire.products.product-list', compact('products'));
    }
}
