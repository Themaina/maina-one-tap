<?php

// Resolve the plugin classes without needing a full Botble install.
spl_autoload_register(function (string $class): void {
    $prefix = 'MainaSuperintelligence\\OneTap\\';

    if (str_starts_with($class, $prefix)) {
        $file = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

        is_file($file) && require $file;
    }
});

// Inside a Botble project, reuse its vendor directory.
foreach ([__DIR__ . '/../../../../vendor/autoload.php', __DIR__ . '/../vendor/autoload.php'] as $autoload) {
    is_file($autoload) && require_once $autoload;
}
