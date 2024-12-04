<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomIndex = rand(0, 3);
        return [
            'gender'=> rand(Gender::ALL[$randomIndex]),
            'join_date'=>fake()->date(),
            'role'=> Role::inRandomOrder()->first()->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('123456'),
        ];
    }
}
