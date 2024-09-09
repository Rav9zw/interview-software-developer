<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ParkingSpot;
use App\Models\Vehicle;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSession>
 */
class ParkingSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $vehicleSize = $this->faker->numberBetween(1, 3);
        $parkingSpot = ParkingSpot::where('spot_size', $vehicleSize)->inRandomOrder()->first();
        $vehicle = Vehicle::where('size', $vehicleSize)->inRandomOrder()->first();

        $start = $this->faker->dateTimeBetween('-3 day', 'now');
        $end = clone $start;
        $end->modify('+1 hour');

        return [
            'vehicle_id' => $vehicle->id,
            'parking_spot_id' => $parkingSpot->id??null,
            'email' => 'rav86pl@hotmail.com',
            'start_time' => $start,
            'end_time' => $end,
        ];
    }
}
