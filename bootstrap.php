<?php

declare(strict_types=1);

use App\Core\App;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    Dotenv::createImmutable(__DIR__)->safeLoad();
}

date_default_timezone_set('America/Sao_Paulo');

return new App(__DIR__);