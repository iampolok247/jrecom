<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\PaymentlyLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentlyBaseUrl = SiteSetting::getByKey('paymently_base_url', 'https://api.paymently.io/v1');
        $paymentlyApiKey = SiteSetting::getByKey('paymently_api_key', '');
        $paymentlySecretKey = SiteSetting::getByKey('paymently_secret_key', '');
        $paymentlyEnv = SiteSetting::getByKey('paymently_environment', 'sandbox');
        $paymentlyEnabled = (bool) SiteSetting::getByKey('paymently_enabled', true);

        $methods = PaymentMethod::orderBy('order', 'asc')->get();
        $logs = PaymentlyLog::latest()->take(20)->get();

        return view('admin.payment.index', compact(
            'paymentlyBaseUrl',
            'paymentlyApiKey',
            'paymentlySecretKey',
            'paymentlyEnv',
            'paymentlyEnabled',
            'methods',
            'logs'
        ));
    }

    public function updatePaymently(Request $request)
    {
        SiteSetting::setKey('paymently_base_url', $request->paymently_base_url, 'paymently');
        SiteSetting::setKey('paymently_api_key', $request->paymently_api_key, 'paymently');
        SiteSetting::setKey('paymently_secret_key', $request->paymently_secret_key, 'paymently');
        SiteSetting::setKey('paymently_environment', $request->paymently_environment, 'paymently');
        SiteSetting::setKey('paymently_enabled', $request->boolean('paymently_enabled') ? '1' : '0', 'paymently');

        // Also sync state of paymently in payment_methods table
        $pm = PaymentMethod::where('code', 'paymently')->first();
        if ($pm) {
            $pm->update(['is_active' => $request->boolean('paymently_enabled')]);
        }

        return back()->with('success', 'Paymently.io API credentials and settings saved successfully!');
    }

    public function updateMethod(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'merchant_number' => $request->merchant_number,
            'personal_number' => $request->personal_number,
            'instructions' => $request->instructions,
            'fixed_charge' => $request->fixed_charge ?? 0,
            'percent_charge' => $request->percent_charge ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', "Payment method {$method->name} updated!");
    }
}
