<?php
namespace App\Core;

/**
 * Barem Nota 10.02: Sistem CAPTCHA anti-bot
 * Generează provocări matematice verificate pe sesiune la înregistrare.
 */
class Captcha
{
    public static function generate(): array
    {
        Session::start();
        $a = rand(2, 9);
        $b = rand(1, 9);
        $op = ['+', '*'][array_rand(['+', '*'])];
        $ans = ($op === '+') ? ($a + $b) : ($a * $b);

        Session::set('captcha_answer', $ans);

        return [
            'question' => "Cât fac {$a} {$op} {$b} ?",
            'answer'   => $ans
        ];
    }

    public static function validate(?string $input): bool
    {
        Session::start();
        $expected = Session::get('captcha_answer');
        if ($expected === null || $input === null) {
            return false;
        }

        $valid = ((int)trim($input) === (int)$expected);
        Session::remove('captcha_answer');
        return $valid;
    }
}
