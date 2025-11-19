<?php

namespace App\Http\Controllers;

use App\Repositories\PedidoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method void middleware(\Closure|string $middleware)
 */
class PatientController extends Controller
{
  public function __construct(
    private readonly PedidoRepository $pedidoRepository
  ) {
    $this->middleware(function ($request, $next) {
      /** @var \App\Models\User|null $user */
      $user = Auth::user();

      if (!Auth::check() || !$user || !$user->isPatient()) {
        abort(403, 'No tienes permisos de paciente para acceder a esta sección.');
      }
      return $next($request);
    });
  }

  /**
   * Show the patient dashboard
   */
  public function dashboard(Request $request)
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $paciente = $user->paciente;

    // Obtener pedidos activos del paciente
    $pedidosActivos = $this->pedidoRepository->getActiveOrdersForPatient($userId);

    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.dashboard-content', compact('pedidosActivos', 'paciente'))->render()
      ]);
    }

    return view('patient.dashboard', compact('pedidosActivos', 'paciente'));
  }

  /**
   * Show the patient orders page
   */
  public function orders(Request $request)
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $pedidos = $this->pedidoRepository->getPaginatedOrdersForPatient($userId);

    // If AJAX request, return only content
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'html' => view('patient.partials.orders-content', compact('pedidos'))->render()
      ]);
    }

    return view('patient.orders', compact('pedidos'));
  }

  /**
   * Show the patient order history
   */
  public function orderHistory()
  {
    $user = Auth::user();
    $userId = $user->user_id;
    $historial = $this->pedidoRepository->getOrderHistoryForPatient($userId);

    return view('patient.order-history', compact('historial'));
  }

  /**
   * Show the patient profile
   */
  public function profile()
  {
    $user = Auth::user();
    $paciente = $user->paciente;

    return view('patient.profile', compact('user', 'paciente'));
  }

  /**
   * Show the patient penalties
   */
  public function penalties()
  {
    $user = Auth::user();
    $paciente = $user->paciente;

    return view('patient.penalties', compact('paciente'));
  }

  /**
   * Show the patient help page
   */
  public function help()
  {
    return view('patient.help');
  }
}
