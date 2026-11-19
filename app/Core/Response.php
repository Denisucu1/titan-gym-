<?php
namespace App\Core;

/**
 * Gestionează trimiterea de redirecturi HTTP și coduri de stare.
 */
class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): void
    {
        if (strpos($url, 'http') !== 0) {
            $url = rtrim(APP_URL, '/') . '/' . ltrim($url, '/');
        }
        header("Location: " . $url);
        exit;
    }
}
