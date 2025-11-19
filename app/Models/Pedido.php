<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Pedido extends Model
{
  use HasRelationships;
  protected $table = 'pedidos';
  protected $primaryKey = 'pedido_id';
  public $timestamps = false;

  protected $fillable = [
    'paciente_id',
    'cadena_id',
    'sucursal_id',
    'fecha_pedido',
    'fecha_entrega',
    'estado',
    'costo_total',
  ];

  protected $casts = [
    'fecha_pedido' => 'date',
    'fecha_entrega' => 'date',
    'costo_total' => 'decimal:2',
  ];

  public function paciente(): BelongsTo
  {
    return $this->belongsTo(Paciente::class, 'paciente_id', 'user_id');
  }

  /**
   * Get the sucursal for this pedido (manual relation due to composite keys)
   */
  public function sucursal()
  {
    return Sucursal::where('cadena_id', $this->cadena_id)
      ->where('sucursal_id', $this->sucursal_id)
      ->first();
  }

  /**
   * Get sucursal relation as query builder (for eager loading workaround)
   */
  public function getSucursalAttribute()
  {
    if (!isset($this->attributes['_sucursal_loaded'])) {
      $this->attributes['_sucursal'] = Sucursal::where('cadena_id', $this->cadena_id)
        ->where('sucursal_id', $this->sucursal_id)
        ->first();
      $this->attributes['_sucursal_loaded'] = true;
    }
    return $this->attributes['_sucursal'] ?? null;
  }

  public function lineasPedidos(): HasMany
  {
    return $this->hasMany(LineaPedido::class, 'pedido_id', 'pedido_id');
  }

  public function rutaRecoleccion(): HasMany
  {
    return $this->hasMany(RutaRecoleccion::class, 'pedido_id', 'pedido_id');
  }

  /**
   * Verificar si el pedido pertenece al paciente dado
   */
  public function belongsToPatient(int $userId): bool
  {
    return $this->paciente_id === $userId;
  }

  /**
   * Scope para filtrar pedidos por paciente
   */
  public function scopeForPatient($query, int $userId)
  {
    return $query->where('paciente_id', $userId);
  }
}
