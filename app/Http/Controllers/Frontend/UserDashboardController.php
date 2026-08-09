<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return view('frontend.user.dashboard', compact('user', 'recentOrders', 'totalOrdersCount', 'wishlistCount'));
    }

    public function orders()
    {
        $orders = Order::with('items')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('frontend.user.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = Order::with(['items.product', 'timelines'])->where('order_number', $orderNumber)->where('user_id', auth()->id())->firstOrFail();
        return view('frontend.user.order-detail', compact('order'));
    }

    public function printInvoice($orderNumber)
    {
        $order = Order::with(['items', 'user'])->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.user.invoice', compact('order'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('frontend.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
        ]);

        $user->update($request->only(['name', 'phone', 'address', 'city', 'state', 'zip', 'country']));

        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with('product')->where('user_id', auth()->id())->get();
        return view('frontend.user.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Request $request, $productId)
    {
        if (!auth()->check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please login to add items to your wishlist.']);
            }
            return redirect()->route('login');
        }

        $exists = Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->first();
        if ($exists) {
            $exists->delete();
            $added = false;
            $msg = 'Removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $productId]);
            $added = true;
            $msg = 'Added to wishlist!';
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'added' => $added, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }
}
