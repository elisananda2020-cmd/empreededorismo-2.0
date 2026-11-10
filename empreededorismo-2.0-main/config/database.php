<?php

declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'techmaker';
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    if ($user === false || $user === '' || $password === false) {
        error_log('Configure DB_USER e DB_PASSWORD para acessar o banco.');
        throw new RuntimeException('Banco de dados temporariamente indisponível.');
    }

    try {
        $connection = new PDO(
            "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
    } catch (PDOException $exception) {
        error_log('Falha na conexão PDO. Código: ' . $exception->getCode());
        throw new RuntimeException('Banco de dados temporariamente indisponível.');
    }

    return $connection;
}
