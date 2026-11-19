<?php
namespace App\Core;

use App\Exceptions\ValidationException;
use App\Exceptions\AuthException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RateLimitException;

/**
 * Rutare MVC: direcționează cererile HTTP către Controllere și acțiuni.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET']['/' . trim($path, '/')] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST']['/' . trim($path, '/')] = $handler;
    }

    public function dispatch(Request $request, Response $response): void
    {
        // 1. Antete de securitate (Barem Nota 10.04)
        Security::sendSecurityHeaders();

        // 2. Rate limiting (Barem Nota 10.01)
        try {
            RateLimiter::check($request->ip());
        } catch (RateLimitException $e) {
            View::render('errors/error', [
                'pageTitle' => '429 Prea multe cereri',
                'title'     => '429 - Limită atinsă',
                'message'   => $e->getMessage()
            ]);
            return;
        }

        $method = $request->getMethod();
        $path = $request->getPath();

        // Rute exacte
        if (isset($this->routes[$method][$path])) {
            $this->execute($this->routes[$method][$path], [], $request, $response);
            return;
        }

        // Rute dinamice (ex: /classes/{id})
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath) . "$#";
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->execute($handler, $params, $request, $response);
                return;
            }
        }

        $response->setStatusCode(404);
        View::render('errors/404', ['pageTitle' => '404 - Pagina nu a fost găsită']);
    }

    private function execute(array $handler, array $params, Request $request, Response $response): void
    {
        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass($request, $response);

        try {
            call_user_func_array([$controller, $action], $params);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
            $response->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        } catch (AuthException $e) {
            Session::flash('error', $e->getMessage());
            $response->redirect('/login');
        } catch (NotFoundException $e) {
            $response->setStatusCode(404);
            View::render('errors/404', ['pageTitle' => '404 Negăsit', 'message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Logger::log("Eroare necunoscută: " . $e->getMessage(), 'CRITICAL');
            $response->setStatusCode(500);
            View::render('errors/500', [
                'pageTitle' => '500 Eroare Internă',
                'error'     => (APP_ENV === 'development') ? $e->getMessage() : 'A apărut o problemă neașteptată.'
            ]);
        }
    }
}
