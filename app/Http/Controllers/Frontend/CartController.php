<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getTotals();
        return view('frontend.cart', compact('cart', 'totals'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;
        $variant = $request->variant ?? [];

        $this->cartService->add($product, $quantity, $variant);

        if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => "Added {$product->name} to cart!",
                'cart' => $this->cartService->getCart(),
                'totals' => $this->cartService->getTotals(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', "Added {$product->name} to cart!");
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->update($request->key, $request->quantity);

        if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->getCart(),
                'totals' => $this->cartService->getTotals(),
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        $this->cartService->remove($request->key);

        if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->getCart(),
                'totals' => $this->cartService->getTotals(),
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $result = $this->cartService->applyCoupon($request->code);

        if ($request->ajax()) {
            return response()->json($result);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function miniCart()
    {
        return response()->json([
            'cart' => $this->cartService->getCart(),
            'totals' => $this->cartService->getTotals(),
        ]);
    }
}
