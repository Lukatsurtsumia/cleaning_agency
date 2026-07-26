<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Services\CleaningQuoteCalculator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'property_type' => $this->faker->randomElement(['apartment', 'house', 'office']),
            'service_type' => $this->faker->randomElement(array_keys(CleaningQuoteCalculator::SERVICE_BASE_PRICES)),
            'bedrooms' => $this->faker->numberBetween(0, 5),
            'bathrooms' => $this->faker->numberBetween(0, 3),
            'extras' => [],
            'address' => $this->faker->address(),
            'estimated_price' => $this->faker->randomFloat(2, 40, 300),
            'status' => 'new',
        ];
    }
}
