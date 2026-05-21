<?php

/**
 * Entry point Hostinger: letakkan file ini di root folder project (sejajar app/, vendor/, public/).
 * .htaccess di folder yang sama harus mengarahkan request ke public/index.php
 * (lihat deployment/hostinger-portfolio-app-full.htaccess).
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
