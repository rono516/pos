<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PesapalPaymentService;
use App\Http\Requests\OrderStoreRequest;
use App\Models\PesapalPayment;
use Illuminate\Support\Facades\Auth;
// use NyanumbaCodes\Pesapal\Facades\Pesapal as FacadesPesapal;
// use NyanumbaCodes\Pesapal\Pesapal;
use NyanumbaCodes\Pesapal\Pesapal;

// use Illuminate\Http\Client\Request as ClientRequest;
// use Illuminate\Support\Facades\Request;

class OrderController extends Controller
{
    protected $pesapalPaymentService;

    public function __construct(PesapalPaymentService $pesapalPaymentService)
    {
        $this->pesapalPaymentService = $pesapalPaymentService;
    }
    public function index(Request $request)
    {
        $orders = new Order();
        if ($request->start_date) {
            $orders = $orders->where('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        $orders = $orders->with(['items.product', 'payments', 'customer'])->latest()->paginate(10);

        $total = $orders->map(function ($i) {
            return $i->total();
        })->sum();
        $receivedAmount = $orders->map(function ($i) {
            return $i->receivedAmount();
        })->sum();

        // return response()->json($orders);

        return view('orders.index', compact('orders', 'total', 'receivedAmount'));
    }

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id'     => $request->user()->id,
        ]);

        $cart = $request->user()->cart()->get();
        foreach ($cart as $item) {
            $order->items()->create([
                'price'      => $item->price * $item->pivot->quantity,
                'quantity'   => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();

        $pesapalPaymentResponse = $this->pesapalPaymentService->initiatePesaPal($request->amount, $order->id);
        Log::info('Response:', ['response' => $pesapalPaymentResponse]);
        // return redirect()->away($pesapalPaymentResponse['redirect_url']);
        return response()->json([
            'redirect_url' => $pesapalPaymentResponse['redirect_url'],
        ]);
        // $order->payments()->create([
        //     'amount'  => $request->amount,
        //     'user_id' => $request->user()->id,
        // ]);
        // $serving_user = auth()->user()->getFullname();
        // $pdf          = Pdf::loadView('orders.receipt', compact('order', 'serving_user'));
        // Receipt::create([
        //     'order_id'     => $order->id,
        //     'serving_user' => $serving_user,
        // ]);

        // return $pdf->download("receipt-{$order->id}.pdf");
    }
    public function partialPayment(Request $request)
    {
        // return $request;
        $orderId = $request->order_id;
        $amount  = $request->amount;

        // Find the order
        $order = Order::findOrFail($orderId);

        // Check if the amount exceeds the remaining balance
        $remainingAmount = $order->total() - $order->receivedAmount();
        if ($amount > $remainingAmount) {
            return redirect()->route('orders.index')->withErrors('Amount exceeds remaining balance');
        }

        // Save the payment
        DB::transaction(function () use ($order, $amount) {
            $order->payments()->create([
                'amount'  => $amount,
                'user_id' => auth()->user()->id,
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Partial payment of ' . config('settings.currency_symbol') . number_format($amount, 2) . ' made successfully.');
    }

    public function handlePesapallCallback(Request $request){

        Log::info("Pesapal callback received", ['request' => $request->all()]);


        $pesapal  = new Pesapal();
        $paymentStatus = $pesapal->getTransactionStatus($request->input('OrderTrackingId'));
        if($paymentStatus['status_code']  === 1){
            $pesaPalPayment =  PesapalPayment::where('order_tracking_id', $request->input('OrderTrackingId') )->first();
            $order = Order::where('id', $pesaPalPayment->order_id)->first();

            $order->payments()->create([
                'amount'  => $paymentStatus['amount'],
                'user_id' => 1,
            ]);
            // $serving_user = Auth::user()->getFullname();
            // $pdf          = Pdf::loadView('orders.receipt', compact('order', 'serving_user'));
            Receipt::create([
                'order_id'     => $order->id,
                'serving_user' => "Admin",
            ]);

            // return $pdf->download("receipt-{$order->id}.pdf");

        }
    }
}
