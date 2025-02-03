<?php

namespace Database\Factories\TravelOrders;

use App\Models\TravelOrders\TravelOrder;
use App\Models\Register\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelOrders\TravelOrder>
 */
class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition(): array
    {
        return [
            'requester_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+10 days'),
            'return_date' => $this->faker->dateTimeBetween('+11 days', '+20 days'),
            'status' => 'requested',
            'created_by' => User::factory(), 
            'updated_by' => null,
        ];
    }
}
