<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->withCount('orders')->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => !$user->status]);
        return back()->with('success', 'Customer status updated.');
    }
}
