<?php
// ============================================================================
// BAREM NOTA 6.01: TESTE UNITARE (Minimum 5 teste unitare)
// Rulează direct din consolă cu: php tests/UnitTest.php
// ============================================================================

require_once dirname(__DIR__) . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_DIR . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Security;
use App\Core\Session;
use App\Core\Captcha;
use App\Models\GymClass;
use App\Models\User;

class UnitTest
{
    private int $passed = 0;
    private int $failed = 0;

    public function runAll(): void
    {
        echo "========================================================\n";
        echo " RULARE TESTE UNITARE - TITAN GYM (Barem Nota 6.01)\n";
        echo "========================================================\n\n";

        $this->test1_PasswordHashingAndVerification();
        $this->test2_CsrfTokenGenerationAndValidation();
        $this->test3_FileUploadSizeLimitAndExtensions();
        $this->test4_CaptchaGenerationAndValidation();
        $this->test5_GymClassCapacityAndAvailableSpots();
        $this->test6_UserRoleAndAdminCheck();

        echo "\n--------------------------------------------------------\n";
        echo "REZULTATE: {$this->passed} TESTE TRECUTE, {$this->failed} EȘUATE.\n";
        echo "--------------------------------------------------------\n";

        if ($this->failed > 0) exit(1);
        exit(0);
    }

    private function assert(string $name, bool $condition, string $msg = ''): void
    {
        if ($condition) {
            $this->passed++;
            echo " [✓ PASSED] {$name}\n";
        } else {
            $this->failed++;
            echo " [✗ FAILED] {$name}: {$msg}\n";
        }
    }

    public function test1_PasswordHashingAndVerification(): void
    {
        $pwd = "ParolaSecreta123!";
        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        $this->assert(
            "Test 1: Hashing și verificare parolă (BCRYPT)",
            password_verify($pwd, $hash) && !password_verify("ParolaGresita", $hash)
        );
    }

    public function test2_CsrfTokenGenerationAndValidation(): void
    {
        Session::start();
        $token = Security::generateCsrfToken();
        $this->assert(
            "Test 2: Generare și validare Token CSRF",
            Security::validateCsrfToken($token) && !Security::validateCsrfToken("token_fals")
        );
    }

    public function test3_FileUploadSizeLimitAndExtensions(): void
    {
        $max = MAX_UPLOAD_SIZE; // 12 * 1024 * 1024
        $small = 5 * 1024 * 1024;
        $big = 15 * 1024 * 1024;
        $validExt = in_array('jpg', ALLOWED_EXTENSIONS, true);
        $invalidExt = in_array('exe', ALLOWED_EXTENSIONS, true);

        $this->assert(
            "Test 3: Limitare fișiere max 12 MB și whitelist extensii (.jpg, .pdf, .png)",
            ($small <= $max) && ($big > $max) && $validExt && !$invalidExt
        );
    }

    public function test4_CaptchaGenerationAndValidation(): void
    {
        $c = Captcha::generate();
        $this->assert(
            "Test 4: Generare provocare CAPTCHA și validare răspuns",
            !empty($c['question']) && Captcha::validate((string)$c['answer']) && !Captcha::validate("999999")
        );
    }

    public function test5_GymClassCapacityAndAvailableSpots(): void
    {
        $class = new GymClass(['capacity' => 20, 'booked_count' => 15]);
        $available = $class->getAvailableSpots();
        $class->setBookedCount(20);
        $isFull = $class->isFull();

        $this->assert(
            "Test 5: Calcul dinamic capacitate și locuri libere GymClass",
            $available === 5 && $isFull
        );
    }

    public function test6_UserRoleAndAdminCheck(): void
    {
        $admin = new User(['role_id' => 1, 'email' => 'admin@gym.ro']);
        $client = new User(['role_id' => 2, 'email' => 'client@gym.ro']);

        $this->assert(
            "Test 6: Verificare autorizare roluri (Admin vs Client)",
            $admin->isAdmin() && !$client->isAdmin()
        );
    }
}

$runner = new UnitTest();
$runner->runAll();
