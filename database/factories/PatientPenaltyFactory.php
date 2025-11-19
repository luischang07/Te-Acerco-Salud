<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\PatientPenalty;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientPenaltyFactory extends Factory
{
  protected $model = PatientPenalty::class;

  public function definition(): array
  {
    return [
      'patient_id' => \App\Models\User::factory(),
      'reason' => $this->faker->randomElement([
        'Late pickup',
        'Missed appointment',
        'Lost equipment',
        'Damage to property',
        'Violation of terms',
        'Non-payment of previous fees'
      ]),
      'amount' => $this->faker->randomFloat(2, 10, 500),
      'status' => $this->faker->randomElement(['activa', 'pagada', 'liberada']),
      'notes' => $this->faker->optional()->sentence(),
      'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
      'updated_at' => function (array $attributes) {
        return $attributes['created_at'];
      },
    ];
  }
}
