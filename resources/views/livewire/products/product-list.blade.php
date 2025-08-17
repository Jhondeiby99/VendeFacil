<div>
    <!-- Mensajes -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Barra superior -->
    <div class="flex items-center justify-between mb-4">
        <!-- Buscador -->
        <input 
            type="text" 
            wire:model.live.debounce.500ms="search" 
            placeholder="Buscar productos..." 
            class="w-1/3 px-3 py-2 border rounded-lg"
        />

        <!-- Botón para crear producto -->
        <div class="flex justify-left mt-4">
        <a href="{{ route('product-form') }}" 
        class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-40 px-3 my-px text-zinc-500 dark:text-white/80 
                        data-current:text-(--color-accent-content) hover:data-current:text-(--color-accent-content) 
                        data-current:bg-white dark:data-current:bg-white/[7%] 
                        data-current:border data-current:border-zinc-200 dark:data-current:border-transparent 
                        hover:text-zinc-800 dark:hover:text-white dark:hover:bg-white/[7%] 
                        hover:bg-zinc-800/5 border border-transparent transition transform duration-150 hover:scale-105 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
        {{ __('Add New Product') }}
        </a>
        </div>

    </div>

    <!-- Grilla de productos -->
    <div class="grid gap-4 md:grid-cols-3">
        @forelse($products as $product)
            <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
                <h3 class="text-lg font-bold mb-2">{{ $product->name }}</h3>
                <p class="mb-2">{{ $product->description }}</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="font-semibold text-green-600">${{ number_format($product->price, 2) }}</span>
                    <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                </div>

                <!-- Botones -->
                <div class="flex justify-between">
                    <a href="{{ route('product-edit', ['id' => $product->id]) }}" 
                       class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-40 px-3 my-px text-zinc-500 dark:text-white/80 
                        data-current:text-(--color-accent-content) hover:data-current:text-(--color-accent-content) 
                        data-current:bg-white dark:data-current:bg-white/[7%] 
                        data-current:border data-current:border-zinc-200 dark:data-current:border-transparent 
                        hover:text-zinc-800 dark:hover:text-white dark:hover:bg-white/[7%] 
                        hover:bg-zinc-800/5 border border-transparent transition transform duration-150 hover:scale-105 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                        Editar
                    </a>
                    <button 
                    x-data 
                    @click="if (confirm('¿Estás seguro de eliminar este producto?')) { $wire.delete({{ $product->id }}) }"
                    class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-40 px-3 my-px 
                        text-zinc-500 dark:text-white/80 hover:text-zinc-800 dark:hover:text-white 
                        dark:hover:bg-white/[7%] hover:bg-zinc-800/5 border border-transparent 
                        transition transform duration-150 hover:scale-105 shadow hover:shadow-md 
                        focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 cursor-pointer">
                    Eliminar
                </button>

                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">
                No hay productos aún.
            </div>
        @endforelse
    </div>
</div>
