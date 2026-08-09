<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentlyService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymentlyService $paymentlyService;

    public function __construct(CartService $cartService, OrderService $orderService, PaymentlyService $paymentlyService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentlyService = $paymentlyService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your shopping cart is empty.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('order', 'asc')->get();
        $totals = $this->cartService->getTotals(60.00); // default shipping
        $user = auth()->user();

        return view('frontend.checkout', compact('cart', 'paymentMethods', 'totals', 'user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'payment_method_code' => 'required|string',
        ]);

        $cart = $this->cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $paymentMethod = PaymentMethod::where('code', $request->payment_method_code)->first();
        $paymentName = $paymentMethod ? $paymentMethod->name : 'Cash On Delivery';
        
        $shippingCost = 60.00;
        $totals = $this->cartService->getTotals($shippingCost);

        $orderData = array_merge($request->all(), [
            'payment_method_id' => $paymentMethod?->id,
            'payment_method_name' => $paymentName,
            'coupon_code' => $this->cartService->getCouponCode(),
        ]);

        $order = $this->orderService->createOrder($orderData, $cart, $totals);
        $this->cartService->clear();

        // If Paymently.io API selected
        if ($request->payment_method_code === 'paymently' && $this->paymentlyService->isEnabled()) {
            $paymentResult = $this->paymentlyService->createPayment($order);
            if ($paymentResult['success']) {
                return redirect($paymentResult['redirect_url']);
            }
        }

        // For bKash / Nagad / Rocket with TxID
        if (in_array($request->payment_method_code, ['bkash', 'nagad', 'rocket'])) {
            $order->update([
                'transaction_id' => $request->transaction_id ?? null,
                'payment_details' => ['trx_id' => $request->transaction_id, 'user_account' => $request->sender_account],
            ]);
        }

        return redirect()->route('checkout.success', $order->order_number)->with('success', 'Order placed successfully!');
    }

    public function success($orderNumber)
    {
        $order = Order::with('items.product')->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.order-success', compact('order'));
    }
}
