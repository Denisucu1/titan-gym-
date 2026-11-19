<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Core\Security;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;

/**
 * Controller Abstract de Bază - Barem Nota 2.03, 3.01 & 4.01
 * Oferă suport pentru CSRF, randare view și protecție RBAC (Role-based access control).
 */
abstract class BaseController
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function render(string $view, array $data = []): void
    {
        $data['currentUser'] = $this->user();
        $data['flashSuccess'] = Session::getFlash('success');
        $data['flashError'] = Session::getFlash('error');
        $data['csrfToken'] = Security::generateCsrfToken();

        View::render($view, $data);
    }

    /**
     * Barem Nota 9.01: Protecție CSRF automată la trimiterea formularelor
     */
    protected function validateCsrf(): void
    {
        $token = $this->request->post('csrf_token') ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!Security::validateCsrfToken($token)) {
            throw new ValidationException("Sesiunea de securitate a expirat (Token CSRF invalid). Reîncarcă pagina.");
        }
    }

    protected function user(): ?array
    {
        return Session::get('user');
    }

    protected function isAuthenticated(): bool
    {
        return Session::has('user');
    }

    protected function isAdmin(): bool
    {
        $u = $this->user();
        return $u && isset($u['role_id']) && (int)$u['role_id'] === 1;
    }

    /**
     * Barem Nota 3.03 & 3.04: Autentificare și Autorizare
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            Session::flash('error', 'Trebuie să fii autentificat pentru a accesa această pagină.');
            $this->response->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            throw new AuthException("Acces interzis! Această zonă este rezervată exclusiv administratorilor sălii.");
        }
    }
}
