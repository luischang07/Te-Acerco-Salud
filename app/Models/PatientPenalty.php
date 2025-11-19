<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPenalty extends Model
{
  use HasFactory;

  protected $table = 'patient_penalties';

  protected $fillable = [
    'patient_id',
    'reason',
    'amount',
    'status',
    'notes',
  ];

  protected $casts = [
    'amount' => 'decimal:2',
  ];

  public function patient(): BelongsTo
  {
    return $this->belongsTo(Paciente::class, 'patient_id', 'user_id');
  }
}
