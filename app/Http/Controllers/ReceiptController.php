<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
    public function __invoke(Order $order)
    {
        $receipt = $order->receipt;
        $serving_user = $receipt->serving_user;
        $pdf          = Pdf::loadView('orders.receipt', compact('order', 'serving_user'));
        return $pdf->download("receipt-{$order->id}.pdf");
    }
}
