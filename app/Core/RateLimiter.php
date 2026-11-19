<?php
namespace App\Core;

use App\Exceptions\RateLimitException;

/**
 * Barem Nota 10.01: Limitarea cererilor (Rate Limiting)
 * Blochează temporar cererile dacă un IP depășește 100 de cereri într-un minut (HTTP 429).
 */
class RateLimiter
{
    private static string $storageFile = STORAGE_DIR . '/rate_limits.json';

    public static function check(string $ip, int $max = RATE_LIMIT_MAX_REQUESTS, int $window = RATE_LIMIT_WINDOW_SECONDS): void
    {
        $now = time();
        $limits = self::readLimits();

        if (!isset($limits[$ip])) {
            $limits[$ip] = [];
        }

        // Eliminăm timestamp-urile mai vechi de fereastra de 60 secunde
        $limits[$ip] = array_filter($limits[$ip], function ($t) use ($now, $window) {
            return ($now - $t) < $window;
        });

        if (count($limits[$ip]) >= $max) {
            Logger::log("Rate limit depășit de IP: {$ip}", 'SECURITY_WARNING');
            http_response_code(429);
            header('Retry-After: ' . $window);
            throw new RateLimitException("Ai depășit limita de {$max} de cereri pe minut. Te rugăm să aștepți 60 de secunde.");
        }

        $limits[$ip][] = $now;
        self::saveLimits($limits);
    }

    private static function readLimits(): array
    {
        if (!file_exists(self::$storageFile)) return [];
        $data = json_decode(@file_get_contents(self::$storageFile) ?: '[]', true);
        return is_array($data) ? $data : [];
    }

    private static function saveLimits(array $limits): void
    {
        $dir = dirname(self::$storageFile);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        @file_put_contents(self::$storageFile, json_encode($limits), LOCK_EX);
    }
}
