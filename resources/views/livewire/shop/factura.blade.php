<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8 mt-10" 
     x-data="{ imprimir() { window.print() } }">

    {{-- Header de la factura --}}
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Factura #{{ $factura->id }}</h1>
            <p class="text-gray-500 text-sm">Emitida el {{ $factura->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-semibold text-gray-700">Cliente</h2>
            <p class="text-gray-900 font-medium">{{ $factura->user?->name ?? 'Invitado' }}</p>
            <p class="text-gray-500 text-sm">{{ $factura->user?->email }}</p>
        </div>
    </div>

    {{-- Detalle de la compra --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border-b">Producto</th>
                    <th class="px-4 py-2 border-b text-center">Cantidad</th>
                    <th class="px-4 py-2 border-b text-right">Precio Unit.</th>
                    <th class="px-4 py-2 border-b text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factura->detalle as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 border-b">{{ $item['nombre'] }}</td>
                        <td class="px-4 py-3 border-b text-center">{{ $item['cantidad'] }}</td>
                        <td class="px-4 py-3 border-b text-right">${{ number_format($item['precio'], 2) }}</td>
                        <td class="px-4 py-3 border-b text-right font-semibold">
                            ${{ number_format($item['precio'] * $item['cantidad'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-100">
                    <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">
                        ${{ number_format($factura->total, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Acciones --}}
    <div class="flex justify-between items-center mt-8">
        <a href="{{ route('shop-dashboard') }}"
           class="bg-blue-600 hover:bg-blue-700  px-6 py-2 rounded-lg shadow">
            🛒 Volver a la tienda
        </a>
        
        <div class="flex gap-3">
            <button @click="imprimir"
                    class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg shadow cursor-pointer">
                🖨️ Imprimir
            </button>
            <button wire:click="descargarFactura"
                class="bg-green-600 px-4 py-2 rounded hover:bg-green-700 cursor-pointer">
                Descargar PDF
            </button>
        </div>
    </div>
</div>
