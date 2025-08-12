<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Skip routing for WordPress admin URLs - let WordPress handle them
if (strpos($path, '/wp-admin') === 0 || strpos($path, '/wp-login') === 0 || strpos($path, '/wp-cron') === 0) {
    return false;
}

$file = __DIR__ . $path;

// If it's a directory, look for index files
if (is_dir($file)) {
    if (file_exists($file . '/index.html')) {
        $file = $file . '/index.html';
    } elseif (file_exists($file . '/index.php')) {
        $file = $file . '/index.php';
    }
}

// Process .html and .php files through PHP
if (file_exists($file) && (pathinfo($file, PATHINFO_EXTENSION) === 'html' || pathinfo($file, PATHINFO_EXTENSION) === 'php')) {
    include $file;
    return true;
}

// For other files, let the server handle them normally
return false;
?>
