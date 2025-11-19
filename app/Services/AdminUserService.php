<?php

namespace App\Services;

use App\Repositories\UserManagementRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminUserService
{
  public function __construct(
    private readonly UserManagementRepository $userRepository
  ) {}

  /**
   * Get users list with filters and pagination
   *
   * @param array $filters
   * @param int $perPage
   * @return array
   */
  public function getUsersList(array $filters = [], int $perPage = 10): array
  {
    $users = $this->userRepository->getPaginatedUsers($perPage, $filters);
    $stats = $this->userRepository->getUserStats();

    // Format users data for view
    $formattedUsers = $users->map(function ($user) {
      return [
        'id' => $user->user_id,
        'nombre' => $user->nombre,
        'correo' => $user->correo,
        'direccion' => $user->direccion,
        'roles' => $this->formatUserRoles($user),
        'status' => $this->getUserStatus($user),
        'ultimo_login' => $user->ultimo_login,
        'created_at' => $user->created_at,
        'is_locked' => $this->isUserLocked($user),
        'is_active' => $this->isUserActive($user),
      ];
    });

    return [
      'users' => $formattedUsers,
      'pagination' => [
        'current_page' => $users->currentPage(),
        'last_page' => $users->lastPage(),
        'per_page' => $users->perPage(),
        'total' => $users->total(),
        'from' => $users->firstItem(),
        'to' => $users->lastItem(),
      ],
      'stats' => $stats,
    ];
  }

  /**
   * Get user details by ID
   *
   * @param int $userId
   * @return array|null
   */
  public function getUserDetails(int $userId): ?array
  {
    $user = $this->userRepository->getUserWithRole($userId);

    if (!$user) {
      return null;
    }

    $roles = $this->userRepository->getUserRoles($userId);

    return [
      'id' => $user->user_id,
      'nombre' => $user->nombre,
      'correo' => $user->correo,
      'direccion' => $user->direccion,
      'roles' => $roles,
      'role_details' => [
        'paciente' => $user->paciente,
        'administrador' => $user->administrador,
        'empleado' => $user->empleado,
      ],
      'status' => $this->getUserStatus($user),
      'ultimo_login' => $user->ultimo_login,
      'ultimo_cierre_sesion' => $user->ultimo_cierre_sesion,
      'login_attempts' => $user->login_attempts,
      'locked_until' => $user->locked_until,
      'created_at' => $user->created_at,
      'is_locked' => $this->isUserLocked($user),
      'is_active' => $this->isUserActive($user),
    ];
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
    return $this->userRepository->lockUser($userId, $minutes);
  }

  /**
   * Unlock user account
   *
   * @param int $userId
   * @return bool
   */
  public function unlockUser(int $userId): bool
  {
    return $this->userRepository->unlockUser($userId);
  }

  /**
   * Delete user
   *
   * @param int $userId
   * @return bool
   */
  public function deleteUser(int $userId): bool
  {
    return $this->userRepository->deleteUser($userId);
  }

  /**
   * Get recent user activity
   *
   * @param int $limit
   * @return array
   */
  public function getRecentActivity(int $limit = 10): array
  {
    $activity = $this->userRepository->getRecentActivity($limit);

    return $activity->map(function ($user) {
      return [
        'id' => $user->user_id,
        'nombre' => $user->nombre,
        'correo' => $user->correo,
        'ultimo_login' => $user->ultimo_login,
        'ultimo_cierre_sesion' => $user->ultimo_cierre_sesion,
      ];
    })->toArray();
  }

  /**
   * Format user roles for display
   *
   * @param \App\Models\User $user
   * @return array
   */
  private function formatUserRoles($user): array
  {
    $roles = [];

    if ($user->paciente) {
      $roles[] = [
        'type' => 'paciente',
        'label' => __('admin.users.roles.patient'),
        'color' => 'blue',
      ];
    }

    if ($user->administrador) {
      $roles[] = [
        'type' => 'administrador',
        'label' => __('admin.users.roles.administrator'),
        'color' => 'purple',
      ];
    }

    if ($user->empleado) {
      $roles[] = [
        'type' => 'empleado',
        'label' => __('admin.users.roles.employee'),
        'color' => 'green',
      ];
    }

    return $roles;
  }

  /**
   * Get user status
   *
   * @param \App\Models\User $user
   * @return string
   */
  private function getUserStatus($user): string
  {
    if ($this->isUserLocked($user)) {
      return 'locked';
    }

    if ($this->isUserActive($user)) {
      return 'active';
    }

    return 'inactive';
  }

  /**
   * Check if user is locked
   *
   * @param \App\Models\User $user
   * @return bool
   */
  private function isUserLocked($user): bool
  {
    return $user->locked_until && $user->locked_until > now();
  }

  /**
   * Check if user is active (has valid session)
   *
   * @param \App\Models\User $user
   * @return bool
   */
  private function isUserActive($user): bool
  {
    return $user->session_token && $user->session_expires_at && $user->session_expires_at > now();
  }
}
