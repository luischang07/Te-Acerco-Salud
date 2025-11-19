<?php

namespace App\Repositories;

use App\Models\PatientPenalty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PenaltyManagementRepository
{
  /**
   * Get paginated penalties with filters
   */
  public function getPaginatedPenalties(int $perPage = 10, array $filters = []): LengthAwarePaginator
  {
    $query = PatientPenalty::with(['patient.user']);

    // Search filter (Patient Name or Reason)
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('reason', 'like', "%{$search}%")
          ->orWhereHas('patient.user', function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('apellido', 'like', "%{$search}%");
          });
      });
    }

    // Status filter
    if (!empty($filters['status'])) {
      $query->where('status', $filters['status']);
    }

    // Default sorting
    $query->orderBy('created_at', 'desc');

    return $query->paginate($perPage);
  }

  /**
   * Get penalty statistics
   */
  public function getPenaltyStats(): array
  {
    $totalPenalties = PatientPenalty::count();
    $activePenalties = PatientPenalty::where('status', 'active')->count();
    $resolvedPenalties = PatientPenalty::where('status', 'paid')->orWhere('status', 'waived')->count();

    // Calculate average resolution time (mock for now or complex query)
    // For simplicity, we'll return a placeholder or simple calculation if needed
    $avgResolutionDays = 0;

    return [
      'total_penalties' => $totalPenalties,
      'active_penalties' => $activePenalties,
      'resolved_penalties' => $resolvedPenalties,
      'avg_resolution_days' => $avgResolutionDays,
    ];
  }
}
