<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\PatientPenalty;
use Illuminate\Database\Seeder;

class PenaltySeeder extends Seeder
{
  public function run(): void
  {
    $patients = Paciente::all();

    if ($patients->isEmpty()) {
      return;
    }

    foreach ($patients as $patient) {
      // Create 1-3 penalties for each patient
      PatientPenalty::factory()->count(rand(1, 3))->create([
        'patient_id' => $patient->user_id
      ]);
    }
  }
}
