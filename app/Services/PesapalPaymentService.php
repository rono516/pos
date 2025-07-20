<?php
namespace App\Services;

use App\Models\PesapalPayment;
use NyanumbaCodes\Pesapal\Pesapal; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PesapalPaymentService
{
    public function initiatePesaPal($amount, $orderId)
    {
        try {
            $pesapal  = new Pesapal();
            $response = $pesapal->submitOrderRequest(
                amount: $amount,
                description: "payment description",
                billing_address: ['email_address' => Auth::user()->email],
                notificationId: 'a8153a9e-abfd-4ea1-aa2c-db8c8bfb4092',
            );

            PesapalPayment::create([
                'order_id'           => $orderId,
                'order_tracking_id'  => $response['order_tracking_id'],
                'merchant_reference' => $response['merchant_reference'],
                'redirect_url'       => $response['redirect_url'],
                'status'             => $response['status'],
            ]);
            return $response;
            // return redirect()->away($response['redirect_url']);
        } catch (\Exception $e) {
            report($e);
            return false;
        }

    }

    public function handleCallback(Request $request){
        $notificationType = $request->input('OrderNotificationType');
        $trackingId = $request->input('OrderTrackingId');
        $merchantReference = $request->input('OrderMerchantReference');

        $pesapal = new Pesapal();
        $paymentStatus = $pesapal->getTransactionStatus($trackingId);

        if($paymentStatus["status_code"] === 1){

        }
    }
}
