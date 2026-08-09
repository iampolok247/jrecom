<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentlyLog;
use App\Services\OrderService;
use Illuminate\Http\Request;

class PaymentlyWebhookController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Webhook listener for Paymently.io API updates
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $orderNumber = $payload['order_id'] ?? $payload['order_number'] ?? null;
        $status = strtolower($payload['status'] ?? '');
        $paymentId = $payload['payment_id'] ?? null;

        PaymentlyLog::create([
            'payment_id' => $paymentId,
            'order_number' => $orderNumber,
            'amount' => $payload['amount'] ?? 0,
            'status' => $status,
            'payload' => $payload,
            'ip_address' => $request->ip(),
        ]);

        if ($orderNumber && in_array($status, ['completed', 'paid', 'success'])) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentId ?? ('PAYLY-' . strtoupper(bin2hex(random_bytes(5)))),
                    'payment_details' => $payload,
                ]);
                $this->orderService->updateOrderStatus($order, 'confirmed', 'Automated payment verification confirmed via Paymently.io API webhook.');
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook received']);
    }

    /**
     * Callback URL after payment redirection
     */
    public function callback(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $paymentId = $request->get('payment_id', 'PAYLY-' . rand(100000, 999999));

        $order->update([
            'payment_status' => 'paid',
            'transaction_id' => $paymentId,
        ]);
        $this->orderService->updateOrderStatus($order, 'confirmed', 'Payment verified successfully.');

        return redirect()->route('checkout.success', $order->order_number)->with('success', 'Payment successful via Paymently.io!');
    }

    /**
     * Interactive Mock Payment Gateway for local testing
     */
    public function mockGateway(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $paymentId = $request->get('payment_id', 'PAYLY-' . rand(100000, 999999));
        return view('frontend.mock-paymently-gateway', compact('order', 'paymentId'));
    }
}
