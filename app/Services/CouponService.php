<?php

namespace App\Services;

use App\Repositories\CouponRepository;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;


class CouponService
{
    protected $couponRepository;


    public function __construct(CouponRepository $couponRepository )
    {
        $this->couponRepository = $couponRepository;
    }

    public function store(array $data)
    {
        $coupon = $this->couponRepository->store($data);
        return $coupon;
    }


    public function update(array $data)
    {


        if($data->is_active=='Active')
        $data->is_active = 1;
        else
        $data->is_active = 0;

        Log::info($data->is_active);


        $coupon = $this->couponRepository->update($data);
        return $coupon;
    }

    public function getall( )
    {
        $coupons = $this->couponRepository->getall();
        return $coupons;
    }

    public function destroy($id )
    {
        $coupons = $this->couponRepository->destroy($id);
        return $coupons;
    }

}
