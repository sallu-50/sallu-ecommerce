<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error_message', 'Invalid coupon code.');
        }

        if ($coupon->expires_at && $coupon->expires_at < Carbon::now()) {
            return back()->with('error_message', 'Coupon has expired.');
        }

        // Store the valid coupon in the session
        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->type === 'fixed' ? $coupon->value : $coupon->percent_off,
        ]);

        return back()->with('success_message', 'Coupon applied successfully!');
    }

    public function destroy()
    {
        session()->forget('coupon');
        return back()->with('success_message', 'Coupon removed.');
    }
}
