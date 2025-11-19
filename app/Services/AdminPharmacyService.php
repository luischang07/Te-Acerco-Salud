<?php

namespace App\Services;

use App\Repositories\PharmacyManagementRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminPharmacyService
{
  public function __construct(
    private PharmacyManagementRepository $pharmacyRepository
  ) {}

  /**
   * Get pharmacies list with filters
   */
  public function getPharmaciesList(array $filters = [], int $perPage = 15): array
  {
    $pharmacies = $this->pharmacyRepository->getPaginatedPharmacies($perPage, $filters);
    $stats = $this->pharmacyRepository->getPharmacyStats();
    $chains = $this->pharmacyRepository->getAllChains();

    return [
      'pharmacies' => $pharmacies,
      'stats' => $stats,
      'chains' => $chains,
      'filters' => $filters,
    ];
  }

  /**
   * Get pharmacy details
   */
  public function getPharmacyDetails(string $cadenaId, string $sucursalId): ?array
  {
    $pharmacy = $this->pharmacyRepository->getPharmacyDetails($cadenaId, $sucursalId);

    if (!$pharmacy) {
      return null;
    }

    return [
      'pharmacy' => $pharmacy,
      'monthly_orders' => $this->pharmacyRepository->getMonthlyOrders($cadenaId, $sucursalId),
    ];
  }

  /**
   * Format pharmacy status based on activity
   */
  public function getPharmacyStatus(int $totalOrders): string
  {
    return $totalOrders > 0 ? 'active' : 'inactive';
  }

  /**
   * Delete pharmacy
   */
  public function deletePharmacy(string $cadenaId, string $sucursalId): bool
  {
    return $this->pharmacyRepository->deletePharmacy($cadenaId, $sucursalId);
  }
}
