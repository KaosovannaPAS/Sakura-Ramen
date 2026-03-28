<?php

// Include Composer's Autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $base_dir = __DIR__ . '/../noyau_backend/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) return;
        
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    });
}

use App\Core\Router;

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        putenv(trim($line));
    }
}

$router = new Router();

$router->add('GET', '/', ['App\Controllers\HomeController', 'index']);
$router->add('GET', '/le-restaurant', ['App\Controllers\PageController', 'restaurant']);
$router->add('GET', '/equipe', ['App\Controllers\PageController', 'team']);
$router->add('GET', '/carte', ['App\Controllers\MenuController', 'index']);
$router->add('GET', '/contact', ['App\Controllers\ContactController', 'index']);
$router->add('POST', '/contact', ['App\Controllers\ContactController', 'send']);
$router->add('GET', '/commander', ['App\Controllers\OrderController', 'index']);

$router->add('GET', '/admin', ['App\Controllers\Admin\DashboardController', 'index']);
$router->add('GET', '/admin/login', ['App\Controllers\Admin\AuthController', 'login']);
$router->add('POST', '/admin/login', ['App\Controllers\Admin\AuthController', 'authenticate']);
$router->add('GET', '/admin/logout', ['App\Controllers\Admin\AuthController', 'logout']);

$router->add('GET', '/admin/menu', ['App\Controllers\Admin\MenuController', 'index']);
$router->add('GET', '/admin/menu/add', ['App\Controllers\Admin\MenuController', 'create']);
$router->add('POST', '/admin/menu/add', ['App\Controllers\Admin\MenuController', 'store']);
$router->add('GET', '/admin/menu/edit/{id}', ['App\Controllers\Admin\MenuController', 'edit']);
$router->add('POST', '/admin/menu/edit/{id}', ['App\Controllers\Admin\MenuController', 'update']);
$router->add('GET', '/admin/menu/delete/{id}', ['App\Controllers\Admin\MenuController', 'delete']);

$router->add('GET', '/admin/team', ['App\Controllers\Admin\TeamController', 'index']);
$router->add('GET', '/admin/team/add', ['App\Controllers\Admin\TeamController', 'create']);
$router->add('POST', '/admin/team/add', ['App\Controllers\Admin\TeamController', 'store']);
$router->add('GET', '/admin/team/edit/{id}', ['App\Controllers\Admin\TeamController', 'edit']);
$router->add('POST', '/admin/team/edit/{id}', ['App\Controllers\Admin\TeamController', 'update']);
$router->add('GET', '/admin/team/delete/{id}', ['App\Controllers\Admin\TeamController', 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
