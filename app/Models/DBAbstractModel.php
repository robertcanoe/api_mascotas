<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

abstract class DBAbstractModel
{
    private static ?PDO $db = null;

    protected function getConnection(): PDO
    {
        if (self::$db instanceof PDO) {
            return self::$db;
        }

        $driver = strtolower((string) env('DB_DRIVER', 'pgsql'));
        $host = (string) env('DB_HOST', '127.0.0.1');
        $port = (string) env('DB_PORT', $driver === 'pgsql' ? '5432' : '3306');
        $dbName = (string) env('DB_NAME', '');
        $charset = (string) env('DB_CHARSET', $driver === 'pgsql' ? 'UTF8' : 'utf8mb4');
        $sslMode = (string) env('DB_SSLMODE', 'prefer');
        $schema = (string) env('DB_SCHEMA', 'public');
        $user = (string) env('DB_USER', $driver === 'pgsql' ? 'postgres' : 'root');
        $pass = (string) env('DB_PASS', '');

        $dsn = $this->buildDsn($driver, $host, $port, $dbName, $charset, $sslMode);

        try {
            self::$db = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            if ($driver === 'pgsql') {
                $safeCharset = preg_replace('/[^a-zA-Z0-9_]/', '', strtoupper($charset));
                if ($safeCharset !== '') {
                    self::$db->exec("SET client_encoding TO '" . $safeCharset . "'");
                }

                if ($schema !== '') {
                    $safeSchema = preg_replace('/[^a-zA-Z0-9_]/', '', $schema);
                    if ($safeSchema !== '') {
                        self::$db->exec('SET search_path TO ' . $safeSchema);
                    }
                }
            }
        } catch (PDOException $exception) {
            $dbException = new DatabaseException('Unable to connect to database.', 0, $exception);
            $dbException->logError();
            throw $dbException;
        }

        return self::$db;
    }

    protected function execute_single_query(string $query, array $params = []): ?array
    {
        try {
            $stmt = $this->getConnection()->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();

            return is_array($result) ? $result : null;
        } catch (PDOException $exception) {
            $dbException = new DatabaseException('Error executing single query.', 0, $exception);
            $dbException->logError();
            throw $dbException;
        }
    }

    protected function get_results_from_query(string $query, array $params = []): array
    {
        try {
            $stmt = $this->getConnection()->prepare($query);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            return is_array($rows) ? $rows : [];
        } catch (PDOException $exception) {
            $dbException = new DatabaseException('Error fetching query results.', 0, $exception);
            $dbException->logError();
            throw $dbException;
        }
    }

    protected function execute_non_query(string $query, array $params = []): int
    {
        try {
            $stmt = $this->getConnection()->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $exception) {
            $dbException = new DatabaseException('Error executing write query.', 0, $exception);
            $dbException->logError();
            throw $dbException;
        }
    }

    protected function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    protected function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    protected function rollback(): bool
    {
        return $this->getConnection()->rollBack();
    }

    protected function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    private function buildDsn(
        string $driver,
        string $host,
        string $port,
        string $dbName,
        string $charset,
        string $sslMode
    ): string {
        if ($driver === 'pgsql') {
            return sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
                $host,
                $port,
                $dbName,
                $sslMode
            );
        }

        return sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $dbName, $charset);
    }
}
