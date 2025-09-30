<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\User; // Assuming coupons are related to users
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('COUPON-####'), // Generates a unique coupon code
            'discount' => $this->faker->numberBetween(5, 50), // Generates a random discount between 5% and 50%
            'is_active' => $this->faker->boolean(), // Randomly set if the coupon is active
        ];
    }
}
