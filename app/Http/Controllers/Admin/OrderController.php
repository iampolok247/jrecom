<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with('user');
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('billing_name', 'like', "%{$search}%")
                  ->orWhere('billing_phone', 'like', "%{$search}%");
            });
        }
        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'timelines', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);
        $this->orderService->updateOrderStatus($order, $request->order_status, $request->comment);

        if ($request->filled('payment_status')) {
            $order->update(['payment_status' => $request->payment_status]);
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function printInvoice($id)
    {
        $order = Order::with(['items', 'user'])->findOrFail($id);
        return view('frontend.user.invoice', compact('order'));
    }
}
