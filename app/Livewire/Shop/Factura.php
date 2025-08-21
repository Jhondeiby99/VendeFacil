<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Factura as FacturaModel;
use Barryvdh\DomPDF\Facade\Pdf;

class Factura extends Component
{
    public $factura;

    public function mount(FacturaModel $factura) // recibe modelo directamente
    {
        $this->factura = $factura;
    }


    public function descargarFactura()
{
    $pdf = Pdf::loadView('facturas.pdf', [
        'factura' => $this->factura
    ]);

    return response()->streamDownload(
        fn () => print($pdf->output()),
        'factura_'.$this->factura->id.'.pdf'
    );
}


    public function render()
    {
        return view('livewire.shop.factura');
    }
}
