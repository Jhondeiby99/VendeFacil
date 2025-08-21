<div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

    <h1 class="text-2xl font-bold mb-4">Finalizar compra</h1>

    <div>
        <h2 class="text-lg font-semibold mb-2">Resumen del carrito</h2>
        <ul class="divide-y">
            @foreach($cartItems as $item)
                <li class="py-2 flex justify-between">
                    <span>{{ $item->name }} (x{{ $item->cart_quantity }})</span>
                    <span>${{ number_format($item->cart_price * $item->cart_quantity, 2) }}</span>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 flex justify-between font-bold">
            <span>Total:</span>
            <span>${{ number_format($cartTotal, 2) }}</span>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Datos de pago</h2>
        <form wire:submit.prevent="procesarPago" x-data="{ metodo: 'card' }">
            <label class="block mb-2">
                Nombre completo
                <input type="text" class="w-full border rounded p-2" required>
            </label>

            <label class="block mb-2">
                Dirección
                <input type="text" class="w-full border rounded p-2" required>
            </label>

            <div class="mt-4">
                <span class="font-semibold">Método de pago:</span>
                <div class="flex gap-4 mt-2">
                    <label>
                        <input type="radio" name="metodo" value="card" x-model="metodo"> Tarjeta
                    </label>
                    <label>
                        <input type="radio" name="metodo" value="paypal" x-model="metodo"> PayPal
                    </label>
                </div>
            </div>

            <template x-if="metodo === 'card'">
                <div class="mt-4">
                    <input type="text" placeholder="Número de tarjeta" class="w-full border rounded p-2 mb-2">
                    <div class="flex gap-2">
                        <input type="text" placeholder="MM/AA" class="w-1/2 border rounded p-2">
                        <input type="text" placeholder="CVC" class="w-1/2 border rounded p-2">
                    </div>
                </div>
            </template>

            <flux:button type="submit" variant="primary" class="mt-6 w-full">
                Pagar ahora
            </flux:button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    window.addEventListener('pago-realizado', e => {
        Swal.fire({
            icon: 'success',
            title: '¡Pago realizado!',
            text: 'Gracias por tu compra',
            showConfirmButton: false,
            timer: 2500
        }).then(() => {
            window.location.href = `/factura/${e.detail.idFactura}`;
        });
    });
    </script>

</div>



</div>
