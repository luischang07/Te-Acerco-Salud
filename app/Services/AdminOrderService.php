<?php

namespace App\Services;

use App\Repositories\OrderManagementRepository;

class AdminOrderService
{
  public function __construct(
    private OrderManagementRepository $orderRepository
  ) {
  }

  /**
   * Get orders list with filters
   */
  public function getOrdersList(array $filters = [], int $perPage = 10): array
  {
    $orders = $this->orderRepository->getPaginatedOrders($perPage, $filters);
    $stats = $this->orderRepository->getOrderStats();
    $chains = $this->orderRepository->getAllChains();

    return [
      'orders' => $orders,
      'stats' => $stats,
      'chains' => $chains,
      'filters' => $filters,
    ];
  }
}
