<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Administrador;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class UserManagementRepository
{
  /**
   * Get paginated users with their roles
   *
   * @param int $perPage
   * @param array $filters
   * @return LengthAwarePaginator
   */
  public function getPaginatedUsers(int $perPage = 10, array $filters = []): LengthAwarePaginator
  {
    $query = User::query()
      ->select('users.*')
      ->with(['paciente', 'administrador', 'empleado']);

    // Filter by search term (nombre or correo)
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('nombre', 'like', "%{$search}%")
          ->orWhere('correo', 'like', "%{$search}%");
      });
    }

    // Filter by role
    if (!empty($filters['role'])) {
      switch ($filters['role']) {
        case 'paciente':
          $query->whereHas('paciente');
          break;
        case 'administrador':
          $query->whereHas('administrador');
          break;
        case 'empleado':
          $query->whereHas('empleado');
          break;
        default:
          // No filter applied for unknown role
          break;
      }
    }

    // Filter by status
    if (!empty($filters['status'])) {
      if ($filters['status'] === 'active') {
        $query->whereNotNull('session_token');
      } elseif ($filters['status'] === 'inactive') {
        $query->whereNull('session_token');
      } elseif ($filters['status'] === 'locked') {
        $query->whereNotNull('locked_until')
          ->where('locked_until', '>', now());
      }
    }

    // Order by
    $orderBy = $filters['order_by'] ?? 'created_at';
    $orderDirection = $filters['order_direction'] ?? 'desc';
    $query->orderBy($orderBy, $orderDirection);

    return $query->paginate($perPage);
  }

  /**
   * Get user statistics
   *
   * @return array
   */
  public function getUserStats(): array
  {
    $totalUsers = User::count();
    $totalPacientes = Paciente::count();
    $totalAdministradores = Administrador::count();
    $totalEmpleados = Empleado::count();

    $activeUsers = User::whereNotNull('session_token')
      ->where('session_expires_at', '>', now())
      ->count();

    $lockedUsers = User::whereNotNull('locked_until')
      ->where('locked_until', '>', now())
      ->count();

    // New users this month
    $newUsersThisMonth = User::whereYear('created_at', now()->year)
      ->whereMonth('created_at', now()->month)
      ->count();

    // New users last month
    $newUsersLastMonth = User::whereYear('created_at', now()->subMonth()->year)
      ->whereMonth('created_at', now()->subMonth()->month)
      ->count();

    if ($newUsersLastMonth > 0) {
      $growthPercentage = round((($newUsersThisMonth - $newUsersLastMonth) / $newUsersLastMonth) * 100, 1);
    } elseif ($newUsersThisMonth > 0) {
      $growthPercentage = 100.0;
    } else {
      $growthPercentage = 0.0;
    }

    return [
      'total' => $totalUsers,
      'pacientes' => $totalPacientes,
      'administradores' => $totalAdministradores,
      'empleados' => $totalEmpleados,
      'active' => $activeUsers,
      'locked' => $lockedUsers,
      'new_this_month' => $newUsersThisMonth,
      'growth_percentage' => $growthPercentage,
    ];
  }

  /**
   * Get user by ID with role information
   *
   * @param int $userId
   * @return User|null
   */
  public function getUserWithRole(int $userId): ?User
  {
    return User::with(['paciente', 'administrador', 'empleado'])
      ->where('user_id', $userId)
      ->first();
  }

  /**
   * Get user role(s)
   *
   * @param int $userId
   * @return array
   */
  public function getUserRoles(int $userId): array
  {
    $roles = [];

    if (Paciente::where('user_id', $userId)->exists()) {
      $roles[] = 'paciente';
    }
    if (Administrador::where('user_id', $userId)->exists()) {
      $roles[] = 'administrador';
    }
    if (Empleado::where('user_id', $userId)->exists()) {
      $roles[] = 'empleado';
    }

    return $roles;
  }

  /**
   * Lock user account
   *
   * @param int $userId
   * @param int $minutes
   * @return bool
   */
  public function lockUser(int $userId, int $minutes = 60): bool
  {
    return DB::transaction(function () use ($userId, $minutes) {
      return User::where('user_id', $userId)
        ->lockForUpdate()
        ->update([
          'locked_until' => now()->addMinutes($minutes),
          'session_token' => null,
          'session_expires_at' => null,
        ]) > 0;
    });
  }

  /**
   * Unlock user account
   *
   * @param int $userId
   * @return bool
   */
  public function unlockUser(int $userId): bool
  {
    return DB::transaction(function () use ($userId) {
      return User::where('user_id', $userId)
        ->lockForUpdate()
        ->update([
          'locked_until' => null,
          'login_attempts' => 0,
          'login_attempts_reset_at' => null,
        ]) > 0;
    });
  }

  /**
   * Delete user and related role data
   *
   * @param int $userId
   * @return bool
   */
  public function deleteUser(int $userId): bool
  {
    return DB::transaction(function () use ($userId) {
      // Delete role-specific data
      Paciente::where('user_id', $userId)->delete();
      Administrador::where('user_id', $userId)->delete();
      Empleado::where('user_id', $userId)->delete();

      // Delete user
      return User::where('user_id', $userId)->delete() > 0;
    });
  }

  /**
   * Get recent user activity
   *
   * @param int $limit
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getRecentActivity(int $limit = 10)
  {
    return User::select('user_id', 'nombre', 'correo', 'ultimo_login', 'ultimo_cierre_sesion')
      ->whereNotNull('ultimo_login')
      ->orderBy('ultimo_login', 'desc')
      ->limit($limit)
      ->get();
  }
}
