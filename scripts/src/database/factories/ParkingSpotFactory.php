<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSpot>
 */
class ParkingSpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $spotCounter = 0;
        $floor = intdiv($spotCounter, 30);
        $spotNumber = ($spotCounter % 30) + 1;
        $spotCounter++;
        $random = $this->faker->numberBetween(1, 100);
        if ($random <= 20) {
            $size = 1;
        } elseif ($random <= 90) {
            $size = 2;
        } else {
            $size = 3;
        }

        return [
            'spot_number' => sprintf('%d.%02d', $floor, $spotNumber),
            'floor' => $floor,
            'spot_size'=>$size
        ];
    }
}
