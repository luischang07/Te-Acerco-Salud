<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('patient_penalties', function (Blueprint $table) {
      $table->id();
      $table->foreignId('patient_id')->constrained('users', 'user_id')->onDelete('cascade');
      $table->string('reason');
      $table->decimal('amount', 8, 2);
      $table->enum('status', ['activa', 'pagada', 'liberada'])->default('activa');
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('patient_penalties');
  }
};
