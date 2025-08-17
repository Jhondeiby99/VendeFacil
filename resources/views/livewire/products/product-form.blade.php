
<div class="max-w-xl mx-auto mt-6 bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-md">
    {{-- Mensaje de éxito --}}
    @if (session()->has('message'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block font-medium mb-1">Name</label>
            <input type="text" wire:model="name" placeholder="Nombre del producto" class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Description</label>
            <textarea wire:model="description" placeholder="Descripción del producto" class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Price</label>
            <input type="number" wire:model="price" step="0.01" placeholder="0.00" class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" />
            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Stock</label>
            <input type="number" wire:model="stock" placeholder="Cantidad disponible" class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" />
            @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('dashboard') }}" 
               class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-20 px-3 my-px text-zinc-500 dark:text-white/80 
                    data-current:text-(--color-accent-content) hover:data-current:text-(--color-accent-content) 
                    data-current:bg-white dark:data-current:bg-white/[7%] 
                    data-current:border data-current:border-zinc-200 dark:data-current:border-transparent 
                    hover:text-zinc-800 dark:hover:text-white dark:hover:bg-white/[7%] 
                    hover:bg-zinc-800/5 border border-transparent transition transform duration-150 hover:scale-105 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
               {{ __('Back') }}
            </a>

           <div class="flex justify-center mt-4">
                <button 
                    type="submit"
                    class="h-10 lg:h-8 relative flex items-center gap-3 rounded-lg py-0 text-start w-full px-3 my-px text-zinc-500 dark:text-white/80 
                        data-current:text-(--color-accent-content) hover:data-current:text-(--color-accent-content) 
                        data-current:bg-white dark:data-current:bg-white/[7%] 
                        data-current:border data-current:border-zinc-200 dark:data-current:border-transparent 
                        hover:text-zinc-800 dark:hover:text-white dark:hover:bg-white/[7%] 
                        hover:bg-zinc-800/5 border border-transparent transition transform duration-150 hover:scale-105 shadow hover:shadow-md 
                        focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 cursor-pointer">
                    {{ $product_id ? __('Update Product') : __('Add Product') }}
                </button>
            </div>



        </div>
    </form>
</div>
