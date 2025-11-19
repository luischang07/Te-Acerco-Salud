<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('lineas_pedidos', function (Blueprint $table) {
      $table->unsignedBigInteger('folio_pedido');
      $table->unsignedBigInteger('id_linea_pedido');
      $table->unsignedBigInteger('medicamento_id');
      $table->integer('cantidad');

      $table->primary(['pedido_id', 'linea_id']);

      $table->unique(['pedido_id', 'medicamento_id']);

      $table->foreign('pedido_id')
        ->references('pedido_id')
        ->on('pedidos');

      $table->foreign('medicamento_id')
        ->references('id')
        ->on('medicamentos');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('lineas_pedidos');
  }
};
