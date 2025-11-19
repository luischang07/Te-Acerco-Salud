<?php

namespace App\Repositories;

use App\Models\Inventario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InventarioRepository
{
  /**
   * Obtener inventario de una sucursal con paginación
   *
   * @param string $cadenaId
   * @param string $sucursalId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPaginatedInventoryForBranch(string $cadenaId, string $sucursalId, int $perPage = 20): LengthAwarePaginator
  {
    return Inventario::forBranch($cadenaId, $sucursalId)
      ->with('medicamento')
      ->paginate($perPage);
  }
}
