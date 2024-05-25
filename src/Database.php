<?php

namespace Me\Task7;

use PDO;
use PDOStatement;

class Database
{
    static string $table = 'Market';
    private static ?Database $instance = null;
    private PDO $connection;

    public function __construct()
    {
        // Створюємо з'єднання з базою даних
        $this->connection = new PDO('sqlite:' . __DIR__ . '/../../database.db');
        // Встановлюємо режим обробки помилок
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Ініціалізуємо базу даних
        $this->initializeDatabase();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function initializeDatabase(): void
    {
        $command = "CREATE TABLE IF NOT EXISTS ".self::$table." (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name varchar(64),
            quantity int,
            price int
        );";



        $this->connection->exec($command);
    }

    public static function executeQuery($sql, array $params = []): ?PDOStatement
    {
        $stmt = self::getInstance()->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function __wakeup() {}
    private function __clone() {}
}
