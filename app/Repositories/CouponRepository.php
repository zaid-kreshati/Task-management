<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;

use App\Models\Coupon;




class CouponRepository
{


    public function store(array $data)
    {

        $coupon= Coupon::create($data);

        return $coupon;

    }

    public function update(array $data)
    {

        Log::info($data);

        $coupon= Coupon::update($data);

        return $coupon;

    }

    public function getall()
    {

        $coupons= Coupon::all();

        return $coupons;

    }

    public function destroy($id)
    {

        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return $coupon;

    }




}
