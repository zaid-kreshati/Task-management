<?php

namespace App\Http\Controllers\web;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class VoucherController extends Controller
{

    public function discount(Request $request)
    {
        Log::info($request);

        $request->validate([
            'code' => 'required|string',
        ]);

        $Coupon = Coupon::where('code', $request->code)->where('is_active', true)->first();

        if ($Coupon) {
            $price = getPrice();
            $discount = $Coupon->discount;
            $newPrice = $price - ($discount * $price / 100);

            return response()->json([
                'success' => true,
                'newPrice' => $newPrice,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Invalid coupon code.'),
            ]);
        }
    }

}
