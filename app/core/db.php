<?php
/* PDO database connection + tiny query helpers (no ORM, no dependencies). */

class DB
{
    private static ?PDO $pdo = null;
    private static string $driver = 'mysql';

    public static function connect(array $cfg): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }
        self::$driver = $cfg['driver'] ?? 'mysql';

        if (self::$driver === 'sqlite') {
            // Local / dependency-free development database (single file).
            $dsn = 'sqlite:' . $cfg['path'];
            self::$pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
            return self::$pdo;
        }

        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['name']};charset={$cfg['charset']}";
        self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return self::$pdo;
    }

    /** Active PDO driver: 'mysql' (production/cPanel) or 'sqlite' (local dev). */
    public static function driver(): string
    {
        return self::$driver;
    }

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            throw new RuntimeException('Database not connected.');
        }
        return self::$pdo;
    }

    /** Run a prepared statement and return the statement. */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Fetch all rows. */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** Fetch a single row (or null). */
    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /** Fetch a single scalar value. */
    public static function value(string $sql, array $params = [])
    {
        return self::run($sql, $params)->fetchColumn();
    }

    /** Insert helper — returns last insert id. */
    public static function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $place = implode(', ', array_map(fn($c) => ":$c", $cols));
        $list  = implode(', ', $cols);
        self::run("INSERT INTO `$table` ($list) VALUES ($place)", $data);
        return (int) self::pdo()->lastInsertId();
    }

    /** Update helper by id. */
    public static function update(string $table, int $id, array $data): void
    {
        $set = implode(', ', array_map(fn($c) => "`$c` = :$c", array_keys($data)));
        $data['__id'] = $id;
        self::run("UPDATE `$table` SET $set WHERE id = :__id", $data);
    }

    public static function delete(string $table, int $id): void
    {
        self::run("DELETE FROM `$table` WHERE id = :id", ['id' => $id]);
    }
}
