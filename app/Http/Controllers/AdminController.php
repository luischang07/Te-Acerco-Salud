<?php

namespace App\Http\Controllers;

use App\Services\AdminDashboardService;
use App\Services\AdminUserService;
use App\Services\AdminPharmacyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string $middleware)
 */
class AdminController extends Controller
{
  protected AdminDashboardService $dashboardService;
  protected AdminUserService $userService;
  protected AdminPharmacyService $pharmacyService;

  /**
   * Verificar que el usuario tenga rol de administrador
   */
  public function __construct(
    AdminDashboardService $dashboardService,
    AdminUserService $userService,
    AdminPharmacyService $pharmacyService
  ) {
    $this->dashboardService = $dashboardService;
    $this->userService = $userService;
    $this->pharmacyService = $pharmacyService;

    $this->middleware(function ($request, $next) {
      /** @var \App\Models\User|null $user */
      $user = Auth::user();

      if (!Auth::check() || !$user || !$user->isAdmin()) {
        abort(403, 'No tienes permisos de administrador para acceder a esta sección.');
      }
      return $next($request);
    });
  }

  /**
   * Show the admin dashboard
   */
  public function dashboard(Request $request)
  {
    $stats = $this->dashboardService->getDashboardStats();
    $registrations = $this->dashboardService->getNewRegistrations();

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.dashboard-content', compact('stats', 'registrations'))->render(),
        'title' => __('admin.dashboard.title')
      ]);
    }

    return view('admin.dashboard', compact('stats', 'registrations'));
  }

  /**
   * Show the user management page
   */
  public function users(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'role' => $request->get('role'),
      'status' => $request->get('status'),
      'order_by' => $request->get('order_by', 'created_at'),
      'order_direction' => $request->get('order_direction', 'desc'),
    ];

    $perPage = $request->get('per_page', 10);
    $data = $this->userService->getUsersList($filters, $perPage);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.users-content', [
          'users' => $data['users'],
          'pagination' => $data['pagination'],
          'stats' => $data['stats'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.users.title')
      ]);
    }

    return view('admin.users', [
      'users' => $data['users'],
      'pagination' => $data['pagination'],
      'stats' => $data['stats'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the pharmacy chains management page
   */
  public function chains(Request $request)
  {
    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.chains-content')->render(),
        'title' => __('admin.chains.title')
      ]);
    }

    return view('admin.chains');
  }

  /**
   * Show the pharmacy management page
   */
  public function pharmacies(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'chain' => $request->get('chain'),
      'status' => $request->get('status'),
    ];

    $perPage = $request->get('per_page', 12);
    $data = $this->pharmacyService->getPharmaciesList($filters, $perPage);

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.pharmacies-content', [
          'pharmacies' => $data['pharmacies'],
          'stats' => $data['stats'],
          'chains' => $data['chains'],
          'filters' => $filters,
        ])->render(),
        'title' => __('admin.pharmacies.title')
      ]);
    }

    return view('admin.pharmacies', [
      'pharmacies' => $data['pharmacies'],
      'stats' => $data['stats'],
      'chains' => $data['chains'],
      'filters' => $filters,
    ]);
  }

  /**
   * Show the orders overview
   */
  public function orders(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'status' => $request->get('status'),
      'pharmacy' => $request->get('pharmacy'),
      'date' => $request->get('date'),
      'per_page' => $request->get('per_page', 10),
    ];

    // Mock stats for now
    $stats = [
      'total_orders' => 1524,
      'pending_orders' => 87,
      'completed_today' => 152,
      'avg_fulfillment_hours' => 2.5
    ];

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.orders-content', compact('filters', 'stats'))->render(),
        'title' => __('admin.orders.title')
      ]);
    }

    return view('admin.orders', compact('filters', 'stats'));
  }

  /**
   * Show the penalties management
   */
  public function penalties(Request $request)
  {
    $filters = [
      'search' => $request->get('search'),
      'status' => $request->get('status'),
      'severity' => $request->get('severity'),
      'pharmacy' => $request->get('pharmacy'),
      'per_page' => $request->get('per_page', 10),
    ];

    // Mock stats for now
    $stats = [
      'total_penalties' => 48,
      'active_penalties' => 15,
      'resolved_penalties' => 33,
      'avg_resolution_days' => 5.2
    ];

    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.penalties-content', compact('filters', 'stats'))->render(),
        'title' => __('admin.penalties.title')
      ]);
    }

    return view('admin.penalties', compact('filters', 'stats'));
  }

  /**
   * Show the reports and analytics
   */
  public function reports(Request $request)
  {
    if ($request->wantsJson()) {
      return response()->json([
        'html' => view('admin.partials.reports-content')->render(),
        'title' => __('admin.reports.title')
      ]);
    }

    return view('admin.reports');
  }
}
