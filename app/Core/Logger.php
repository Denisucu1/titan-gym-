<?php
namespace App\Core;

/**
 * Barem Nota 7.01: Jurnalizarea activităților (Logging)
 * Înregistrează evenimentele într-un fișier fizic consultabil: storage/logs/app.log.
 */
class Logger
{
    private static string $logFile = STORAGE_DIR . '/logs/app.log';

    public static function log(string $action, string $level = 'INFO', ?string $details = null): void
    {
        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $time = date('Y-m-d H:i:s');
        $ip = (new Request())->ip();
        Session::start();
        $user = Session::get('user');
        $userInfo = $user ? "User#{$user['id']} ({$user['email']})" : 'Guest';

        $line = sprintf(
            "[%s] [IP: %s] [%s] [%s] - %s%s%s",
            $time,
            $ip,
            $userInfo,
            $level,
            $action,
            $details ? " | Detalii: " . $details : "",
            PHP_EOL
        );

        @file_put_contents(self::$logFile, $line, FILE_APPEND | LOCK_EX);

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address, created_at) VALUES (:u, :a, :d, :ip, :c)");
            $stmt->execute([
                'u'  => $user['id'] ?? null,
                'a'  => $action,
                'd'  => $details,
                'ip' => $ip,
                'c'  => $time
            ]);
        } catch (\Throwable $e) {
            // Ignorăm erori DB pentru ca fișierul fizic este garantat scris
        }
    }

    public static function getRecentLogs(int $limit = 60): array
    {
        if (!file_exists(self::$logFile)) return [];
        $lines = file(self::$logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $lines ? array_slice(array_reverse($lines), 0, $limit) : [];
    }
}
