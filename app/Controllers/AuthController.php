<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Core\Session;
use App\Core\Captcha;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthException;

/**
 * Controller Autentificare & Înregistrare
 * Include: BCRYPT password hash, CAPTCHA, Logging, CSRF.
 */
class AuthController extends BaseController
{
    private UserRepository $userRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->userRepo = new UserRepository();
    }

    public function showLogin(): void
    {
        if ($this->isAuthenticated()) {
            $this->response->redirect('/');
        }
        $this->render('auth/login', ['pageTitle' => 'Autentificare - Titan Gym']);
    }

    public function login(): void
    {
        $this->validateCsrf();

        $email = $this->request->post('email');
        $pwd = $this->request->post('password');

        if (empty($email) || empty($pwd)) {
            throw new ValidationException("Completează emailul și parola.");
        }

        $user = $this->userRepo->findByEmail($email);
        if (!$user || !password_verify($pwd, $user->getPasswordHash())) {
            Logger::log("Login eșuat pentru {$email}", 'WARNING');
            throw new AuthException("Email sau parolă incorectă.");
        }

        Session::set('user', $user->toArray());
        Logger::log("Login reușit pentru {$email}", 'INFO');
        Session::flash('success', "Bine ai revenit, {$user->getName()}!");

        $this->response->redirect($user->isAdmin() ? '/admin' : '/classes');
    }

    public function showRegister(): void
    {
        if ($this->isAuthenticated()) {
            $this->response->redirect('/');
        }
        $captcha = Captcha::generate();
        $this->render('auth/register', [
            'pageTitle'       => 'Creează Cont Nou - Titan Gym',
            'captchaQuestion' => $captcha['question']
        ]);
    }

    public function register(): void
    {
        $this->validateCsrf();

        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $pwd = $this->request->post('password');
        $pwdConfirm = $this->request->post('password_confirm');
        $phone = $this->request->post('phone');
        $captchaInput = $this->request->post('captcha');

        // 1. Validare CAPTCHA (Nota 10.02)
        if (!Captcha::validate($captchaInput)) {
            throw new ValidationException("Răspunsul la verificarea CAPTCHA este incorect.");
        }

        // 2. Validare date
        if (empty($name) || strlen($name) < 3) {
            throw new ValidationException("Numele trebuie să aibă minimum 3 caractere.");
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Adresă de email invalidă.");
        }
        if (strlen($pwd) < 6) {
            throw new ValidationException("Parola trebuie să aibă minimum 6 caractere.");
        }
        if ($pwd !== $pwdConfirm) {
            throw new ValidationException("Parolele introduse nu coincid.");
        }
        if ($this->userRepo->findByEmail($email) !== null) {
            throw new ValidationException("Există deja un cont cu această adresă de email.");
        }

        // 3. Hash securizat BCRYPT & Salvare
        $userId = $this->userRepo->create([
            'role_id'       => 2, // 2 = Client
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($pwd, PASSWORD_BCRYPT),
            'phone'         => $phone,
            'avatar'        => 'default-avatar.png'
        ]);

        $newUser = $this->userRepo->findById($userId);
        Session::set('user', $newUser->toArray());
        Logger::log("Cont creat pentru {$email} (ID #{$userId})", 'INFO');
        Session::flash('success', "Felicitări! Contul tău a fost creat cu succes.");

        $this->response->redirect('/classes');
    }

    public function logout(): void
    {
        $u = $this->user();
        if ($u) Logger::log("Deconectare {$u['email']}", 'INFO');
        Session::remove('user');
        Session::flash('success', 'Te-ai deconectat cu succes.');
        $this->response->redirect('/');
    }
}
