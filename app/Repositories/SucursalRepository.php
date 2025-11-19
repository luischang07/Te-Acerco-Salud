<?php

namespace App\Repositories;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class SucursalRepository
{
  /**
   * Get total count of pharmacies (sucursales)
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return Sucursal::count();
  }

  /**
   * Get count of active pharmacies
   * (For now, all are considered active as there's no status field)
   *
   * @return int
   */
  public function getActiveCount(): int
  {
    return Sucursal::count();
  }

  /**
   * Get pharmacy growth percentage
   *
   * @return float
   */
  public function getGrowthPercentage(): float
  {
    // Since we don't have created_at, we'll calculate based on total
    // This is a placeholder - adjust based on your business logic
    $total = $this->getTotalCount();
    $lastMonthTotal = $total - rand(1, 5); // Simulated for now

    if ($lastMonthTotal === 0) {
      return $total > 0 ? 100.0 : 0.0;
    }

    return round((($total - $lastMonthTotal) / $lastMonthTotal) * 100, 1);
  }

  /**
   * Get pharmacies by chain
   *
   * @param string $cadenaId
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getByChain(string $cadenaId)
  {
    return Sucursal::where('cadena_id', $cadenaId)
      ->with('cadenaFarmaceutica')
      ->get();
  }

  /**
   * Get pharmacy count by chain
   *
   * @return array
   */
  public function getCountByChain(): array
  {
    return Sucursal::select('cadena_id', DB::raw('COUNT(*) as count'))
      ->groupBy('cadena_id')
      ->get()
      ->toArray();
  }
}
