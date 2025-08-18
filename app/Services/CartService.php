<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /** Obtiene el mapa del carrito actual: [product_id => qty] */
    public function getCartMap(): array
    {
        if (Auth::check()) {
            $cart = $this->getActiveCartForUser(Auth::user());
            return $cart->items()->pluck('quantity', 'product_id')->toArray();
        }

        return Session::get('cart', []);
    }

    /** Obtiene o crea el carrito activo del usuario */
    private function getActiveCartForUser($user): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['status' => 'active']
        );
    }

    /** Agrega un producto al carrito respetando stock */
    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::find($productId);
        if (!$product) return [false, 'Producto no encontrado.'];

        if (Auth::check()) {
            $cart = $this->getActiveCartForUser(Auth::user());

            $item = $cart->items()->firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => 0, 'price' => $product->price]
            );

            $nueva = $item->quantity + $quantity;
            if ($nueva > $product->stock) {
                return [false, "Solo hay {$product->stock} unidades disponibles de {$product->name}."];
            }

            $item->quantity = $nueva;
            $item->price = $product->price;
            $item->save();

            return [true, 'Producto agregado.'];
        }

        // Invitado → usar sesión
        $cart = Session::get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        Session::put('cart', $cart);

        return [true, 'Producto agregado.'];
    }

    /** Actualiza la cantidad de un producto en el carrito */
    public function update(int $productId, int $quantity): array
    {
        $product = Product::find($productId);
        if (!$product) return [false, 'Producto no encontrado.'];

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        if ($quantity > $product->stock) {
            return [false, "Solo hay {$product->stock} unidades disponibles de {$product->name}."];
        }

        if (Auth::check()) {
            $cart = $this->getActiveCartForUser(Auth::user());

            $item = $cart->items()->firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => 0, 'price' => $product->price]
            );

            $item->quantity = $quantity;
            $item->price = $product->price;
            $item->save();

            return [true, 'Cantidad actualizada.'];
        }

        // Invitado
        $cart = Session::get('cart', []);
        $cart[$productId] = $quantity;
        Session::put('cart', $cart);

        return [true, 'Cantidad actualizada.'];
    }

    /** Elimina un producto del carrito */
    public function remove(int $productId): array
    {
        if (Auth::check()) {
            $cart = $this->getActiveCartForUser(Auth::user());
            $cart->items()->where('product_id', $productId)->delete();
            return [true, 'Producto eliminado.'];
        }

        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return [true, 'Producto eliminado.'];
    }

    /** Obtiene los items del carrito (productos + cantidad + precio) */
    public function items()
    {
        if (Auth::check()) {
            $cart = $this->getActiveCartForUser(Auth::user());

            return $cart->items()->with('product')->get()->map(function ($item) {
                $p = $item->product;
                if ($p) {
                    $p->cart_quantity = $item->quantity;
                    $p->cart_price = $item->price;
                    return $p;
                }
                return null;
            })->filter();
        }

        // Invitado
        $cart = Session::get('cart', []);
        $ids  = array_keys($cart);
        return Product::whereIn('id', $ids)->get()->map(function ($p) use ($cart) {
            $p->cart_quantity = $cart[$p->id] ?? 0;
            $p->cart_price = $p->price;
            return $p;
        });
    }

    /** Calcula el total del carrito */
    public function total(): float
    {
        return $this->items()->reduce(function ($carry, $p) {
            return $carry + ($p->cart_price * $p->cart_quantity);
        }, 0.0);
    }

    /** Fusiona carrito de sesión en el carrito del usuario al iniciar sesión */
    public function mergeSessionCartToUser($user): void
    {
        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) return;

        $cart = $this->getActiveCartForUser($user);

        foreach ($sessionCart as $productId => $qty) {
            $product = Product::find($productId);
            if (!$product || $qty <= 0) continue;

            $item = $cart->items()->firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0, 'price' => $product->price]
            );

            $item->quantity = min($item->quantity + $qty, $product->stock);
            $item->price = $product->price;
            $item->save();
        }

        Session::forget('cart');
    }
}
