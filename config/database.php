
<?php

function getPDO()
{
    $host = '127.0.0.1';
    $dbname = 'forum_refactor';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        return new \PDO($dsn, $username, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (\PDOException $e) {
        error_log($e->getMessage());
        throw $e;
    }
}
