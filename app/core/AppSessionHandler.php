<?php

class AppSessionHandler implements SessionHandlerInterface {
    private $pdo;

    public function open($savePath, $sessionName): bool {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        // Create sessions table if it doesn't exist
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS `sessions` (
            `id` VARCHAR(255) NOT NULL,
            `data` TEXT NOT NULL,
            `timestamp` INT NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        return true;
    }

    public function close(): bool {
        $this->pdo = null;
        return true;
    }

    public function read($id): string {
        try {
            $stmt = $this->pdo->prepare("SELECT `data` FROM `sessions` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? $row['data'] : '';
        } catch (Exception $e) {
            return '';
        }
    }

    public function write($id, $data): bool {
        try {
            $timestamp = time();
            $stmt = $this->pdo->prepare("REPLACE INTO `sessions` (`id`, `data`, `timestamp`) VALUES (:id, :data, :timestamp)");
            return $stmt->execute([
                ':id' => $id,
                ':data' => $data,
                ':timestamp' => $timestamp
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy($id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM `sessions` WHERE `id` = :id");
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function gc($maxLifetime): int {
        try {
            $old = time() - $maxLifetime;
            $stmt = $this->pdo->prepare("DELETE FROM `sessions` WHERE `timestamp` < :old");
            $stmt->execute([':old' => $old]);
            return $stmt->rowCount();
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function register() {
        // Only use DB session handler on Vercel
        if (getenv('VERCEL') !== false || isset($_SERVER['VERCEL']) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false)) {
            $handler = new self();
            session_set_save_handler($handler, true);
        }
    }
}
