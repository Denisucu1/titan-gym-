<?php
// ============================================================================
// FRONT CONTROLLER (Titan Gym & Fitness) - Punctul unic de intrare
// ============================================================================

require_once dirname(__DIR__) . '/config/config.php';

// Autoloader PSR-4 curat pentru clasele din namespace-ul App\
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_DIR . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ClassController;
use App\Controllers\BookingController;
use App\Controllers\PaymentController;
use App\Controllers\AdminController;

$request = new Request();
$response = new Response();
$router = new Router();

// ============================================================================
// RUTE APLICAȚIE
// ============================================================================

// Homepage
$router->get('/', [HomeController::class, 'index']);

// Clase (Scenariul 1)
$router->get('/classes', [ClassController::class, 'index']);
$router->get('/classes/{id}', [ClassController::class, 'show']);

// Plăți Stripe & Abonamente (Scenariul 2 & Barem 8.02)
$router->get('/memberships', [PaymentController::class, 'index']);
$router->get('/memberships/checkout/{id}', [PaymentController::class, 'checkout']);
$router->post('/memberships/process', [PaymentController::class, 'process']);

// Rezervări (Scenariul 3)
$router->get('/my-bookings', [BookingController::class, 'myBookings']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->post('/bookings/cancel', [BookingController::class, 'cancel']);

// Autentificare & Înregistrare (Barem 3.03 & 10.02)
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Panou Admin (Scenariul 4 & Barem 3.04 & 7.01)
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/classes', [AdminController::class, 'classes']);
$router->get('/admin/classes/create', [AdminController::class, 'createClass']);
$router->post('/admin/classes/store', [AdminController::class, 'storeClass']);
$router->get('/admin/classes/edit/{id}', [AdminController::class, 'editClass']);
$router->post('/admin/classes/update/{id}', [AdminController::class, 'updateClass']);
$router->post('/admin/classes/delete/{id}', [AdminController::class, 'deleteClass']);
$router->get('/admin/bookings', [AdminController::class, 'bookings']);
$router->get('/admin/logs', [AdminController::class, 'logs']);

$router->dispatch($request, $response);
