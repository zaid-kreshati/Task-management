<?php
namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run()
    {
        // Use the factory to generate data
        Coupon::factory(10)->create(); // Creates 10 instances
    }
}
