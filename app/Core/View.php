<?php
namespace App\Core;

/**
 * Gestionează randarea fișierelor View în layout-ul principal.
 */
class View
{
    public static function render(string $viewPath, array $data = [], string $layout = 'layouts/main'): void
    {
        extract($data);

        $viewFile = VIEW_DIR . '/' . ltrim($viewPath, '/') . '.php';
        if (!file_exists($viewFile)) {
            die("View-ul nu a fost găsit: {$viewFile}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            $layoutFile = VIEW_DIR . '/' . ltrim($layout, '/') . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        echo $content;
    }
}
