<?php

declare(strict_types=1);

use App\Core\Database;
use App\Models\User;

$app = require dirname(__DIR__) . '/bootstrap.php';
unset($app);

$pdo = Database::connection();

$schema = file_get_contents(dirname(__DIR__) . '/database/schema.sql');
$seed = file_get_contents(dirname(__DIR__) . '/database/seed.sql');

if (! is_string($schema) || ! is_string($seed)) {
    fwrite(STDERR, "Falha ao ler os arquivos SQL.\n");
    exit(1);
}

$pdo->exec($schema);
$pdo->exec($seed);

$rootEmail = $_ENV['APP_SETUP_ROOT_EMAIL'] ?? 'root@vortexhost.local';
$rootPassword = $_ENV['APP_SETUP_ROOT_PASSWORD'] ?? 'ChangeMe123!';
$rootName = 'Administrador Root';

$users = new User();

if (! $users->findByEmail($rootEmail)) {
    $users->create($rootName, $rootEmail, $rootPassword, 'root');
    echo "Usuário root criado: {$rootEmail}" . PHP_EOL;
} else {
    echo "Usuário root já existe: {$rootEmail}" . PHP_EOL;
}

echo "Banco inicializado com sucesso." . PHP_EOL;