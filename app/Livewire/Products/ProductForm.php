<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductForm extends Component
{
    public $product_id; // Nuevo campo para identificar si es edición
    public $name;
    public $description;
    public $price;
    public $stock;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ];

    // 🔹 Montar datos si es edición
    public function mount($id = null)
    {
        if ($id) {
            $product = Product::findOrFail($id);
            $this->product_id = $product->id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock = $product->stock;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->product_id) {
            // 🔹 Editar
            $product = Product::findOrFail($this->product_id);
            $product->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
            ]);
            session()->flash('message', 'Producto actualizado exitosamente.');
        } else {
            // 🔹 Crear
            Product::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
            ]);
            session()->flash('message', 'Producto creado exitosamente.');
        }

        // 🔹 Redirigir a la lista
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.products.product-form');
    }
}
