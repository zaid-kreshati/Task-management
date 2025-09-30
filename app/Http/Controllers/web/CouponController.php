<?php
namespace App\Http\Controllers\web;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Coupon\storeCouponRequest;
use App\Http\Requests\Coupon\updateCouponRequest;

use App\Services\CouponService;


class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }


    public function form()
    {

        $coupons=$this->couponService->getall();
        return view('coupon' ,compact('coupons'));
    }



    public function store(storeCouponRequest $request)
    {


        $coupon=$this->couponService->store($request->all());


        return response()->json(['success' => true, 'message' => 'Coupon created successfully!', 'coupon' => $coupon]);
    }



   // Update a coupon
   public function update(updateCouponRequest $request)
   {
    Log::info($request);

    $coupon=$this->couponService->update($request->only('code', 'discount' ,'is_active'));



       return response()->json(['success' => true, 'message' => 'Coupon updated successfully!']);
   }


    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string',
        ]);

        // Find the coupon by code
        $coupon = Coupon::where('code', $request->coupon)->where('is_active', true)->first();

        if (!$coupon) {
            return redirect()->back()->withErrors(['coupon' => 'Invalid or expired coupon code.']);
        }

        // Apply coupon to the logged-in user (or store it in the session for a guest user)
        $user = Auth::user();
        $user->discount = $coupon->percent_off;
        $user->save();

        return redirect()->back()->with('success', 'Coupon applied successfully! You get ' . $coupon->percent_off . '% off.');
    }

    public function destroy(Request $request)
{
    $coupon = Coupon::findOrFail($request->id);
    $coupon->delete();

    return response()->json(['success' => true, 'message' => __('Coupon deleted successfully.')]);
}
}
