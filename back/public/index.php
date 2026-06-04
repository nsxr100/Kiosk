<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$tempDir = __DIR__.'/../storage/framework/tmp';

if (! is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}

putenv('TMP='.$tempDir);
putenv('TEMP='.$tempDir);
putenv('TMPDIR='.$tempDir);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
