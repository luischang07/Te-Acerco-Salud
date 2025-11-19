<?php

namespace App\Services;

use App\Repositories\PenaltyManagementRepository;

class AdminPenaltyService
{
  public function __construct(
    private PenaltyManagementRepository $penaltyRepository
  ) {
  }

  /**
   * Get penalties list with filters
   */
  public function getPenaltiesList(array $filters = [], int $perPage = 10): array
  {
    $penalties = $this->penaltyRepository->getPaginatedPenalties($perPage, $filters);
    $stats = $this->penaltyRepository->getPenaltyStats();

    return [
      'penalties' => $penalties,
      'stats' => $stats,
      'filters' => $filters,
    ];
  }
}
