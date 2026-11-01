<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'first_name'       => $this->faker->firstName(),
            'last_name'        => $this->faker->lastName(),
            'address_line_1'   => $this->faker->streetAddress(),
            'address_line_2'   => null,
            'city'             => $this->faker->city(),
            'state'            => $this->faker->stateAbbr(),
            'postal_code'      => $this->faker->postcode(),
            'country'          => 'United States',
            'phone'            => $this->faker->phoneNumber(),
            'type'             => 'both',
            'is_default'       => false,
        ];
    }

    public function default(): static
    {
        return $this->state(['is_default' => true]);
    }
}
