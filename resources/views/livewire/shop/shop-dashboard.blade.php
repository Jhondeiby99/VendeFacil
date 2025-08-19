<div class="p-4">

    <!-- Buscador -->
    <div class="top-0 z-50 mb-6 flex justify-center gap-4 p-2 shadow">
        <input 
            type="text" 
            wire:model.live.debounce.500ms="search" 
            placeholder="Buscar productos..." 
            class="w-full md:w-1/2 border rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        />
        <!-- Botón flotante para abrir carrito -->
    <flux:modal.trigger class="" :name="'cart-modal'">
        <flux:button  x-data="" x-on:click.prevent="$dispatch('open-modal', 'cart-modal')"
                    class="items-center font-medium justify-center gap-2 whitespace-nowrap disabled:opacity-75 dark:disabled:opacity-75 disabled:cursor-default disabled:pointer-events-none h-10 text-sm rounded-lg px-4 inline-flex bg-white hover:bg-zinc-50 dark:bg-zinc-700 dark:hover:bg-zinc-600/75 text-zinc-800 dark:text-white border border-zinc-200 hover:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600 shadow-xs">
            🛒 {{ count($cart) }}
        </flux:button>
    </flux:modal.trigger>
    </div>
    @if (session()->has('errorp'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                            {{ session('errorp') }}
                        </div>
    @endif

    <div class="p-4 flex flex-col min-h-screen">
        <!-- Lista de productos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="border rounded-lg p-4 flex flex-col">
                        <h3 class="mt-2 font-bold text-lg">{{ $product->name }}</h3>
                        <p class="text-zinc-500 dark:text-white/80 mt-1 truncate">{{ $product->description }}</p>
                        <p class="mt-auto font-semibold">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>

                        <div class="mt-2 flex justify-between gap-4">
                            <!-- Detalle del producto -->
                            <flux:modal.trigger class="" :name="'product-detail-' . $product->id">
                                <flux:button variant="primary" x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'product-detail-{{ $product->id }}')"
                                            class="flex-1 mr-1">
                                    Detalle
                                </flux:button>
                            </flux:modal.trigger>

                            <!-- Agregar al carrito -->
                            <flux:button x-data=""
                                        x-on:click.prevent="$wire.addToCart({{ $product->id }}, 1)"
                                        class="flex-1 ml-1">
                                Agregar
                            </flux:button>
                            <!-- Modal de detalle de producto -->
                    <flux:modal name="product-detail-{{ $product->id }}" focusable class="max-w-lg">
                        <div class="p-4">
                            <h2 class="text-xl font-bold">{{ $product->name }}</h2>
                            {{-- <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="mt-2 h-60 w-full object-cover rounded"> --}}
                            <p class="mt-2">{{ $product->description }}</p>
                            <p class="mt-2 font-semibold">Precio: ${{ number_format($product->price, 2) }}</p>
                            <p class="mt-1 text-sm text-gray-500">Stock disponible: {{ $product->stock }}</p>

                            <div class="mt-4 flex justify-end space-x-2 rtl:space-x-reverse">
                                <flux:modal.close>
                                    <flux:button variant="filled">Cerrar</flux:button>
                                </flux:modal.close>

                                <flux:button  x-data=""
                                            x-on:click.prevent="$wire.addToCart({{ $product->id }}, 1)">
                                    Agregar al carrito
                                </flux:button>
                                
                            </div>
                            @if (session()->has('errorp'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                                    {{ session('errorp') }}
                                </div>
                            @endif
                        </div>
                    </flux:modal>
                        </div>
                    </div>

                    
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        No hay productos aún.
                    </div>
                @endforelse        
            </div>
            <div class="mt-6">
                {{ $products->links() }}
            </div>
    </div>

    <!-- Carrito flotante -->
    <flux:modal name="cart-modal" focusable class="max-w-md">
        <div class="p-4">
            <h2 class="text-xl font-bold">Tu Carrito</h2>

            @if(count($cart))
                <ul class="mt-2 space-y-2">
                    @foreach($cartItems as $item)
                        <li class="flex justify-between items-center border-b pb-2">
                            <div>
                                <span>{{ $item->name }} (x{{ $cart[$item->id] }})</span>
                                <p class="text-xs text-gray-500">Stock disponible: {{ $item->stock }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" x-data=""
                                            x-on:click.prevent="$wire.updateQuantity({{ $item->id }}, {{ $cart[$item->id] - 1 }})">-</flux:button>
                                <span>{{ $cart[$item->id] }}</span>
                                <flux:button size="sm" x-data=""
                                            x-on:click.prevent="$wire.updateQuantity({{ $item->id }}, {{ $cart[$item->id] + 1 }})">+</flux:button>
                                <flux:button variant="danger" size="sm" x-data=""
                                            x-on:click.prevent="$wire.removeFromCart({{ $item->id }})">Quitar</flux:button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 flex justify-between items-center font-bold">
                    <span>Total:</span>
                    <span>${{ number_format($cartTotal, 2) }}</span>
                </div>

                <flux:button variant="primary" class="mt-4 w-full">
                    Proceder a pagar
                </flux:button>
            @else
                <p class="mt-2">Tu carrito está vacío.</p>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </flux:modal>    

</div>
