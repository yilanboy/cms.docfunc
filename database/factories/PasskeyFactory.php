<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Passkey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Passkey>
 */
class PasskeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id'      => Admin::factory(),
            'owner_type'    => Admin::class,
            'name'          => fake()->word,
            'credential_id' => Str::random(),
            'data'          => ['transports' => ['internal']],
        ];
    }
}
