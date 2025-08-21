<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura #{{ $factura->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Factura #{{ $factura->id }}</h1>
    <p>Cliente: {{ $factura->user->name ?? 'Usuario' }}</p>
    <p>Total: ${{ number_format($factura->total, 0, ',', '.') }}</p>

    <h3>Detalle</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->detalle as $item)
            <tr>
                <td>{{ $item['nombre'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>${{ number_format($item['precio'], 0, ',', '.') }}</td>
                <td>${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
