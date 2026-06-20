<?php

class AppSessionHandler implements SessionHandlerInterface {
    private $pdo;
    private $originalData = '';
    private $originalTimestamp = 0;

    public function open($savePath, $sessionName): bool {
        try {
            // Require Database class to reuse the shared PDO connection
            require_once __DIR__ . '/Database.php';
            $this->pdo = Database::getSharedConnection();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function close(): bool {
        $this->pdo = null;
        return true;
    }

    public function read($id): string {
        try {
            $stmt = $this->pdo->prepare("SELECT `data`, `timestamp` FROM `sessions` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) {
                $this->originalData = $row['data'];
                $this->originalTimestamp = (int)$row['timestamp'];
                return $row['data'];
            }
            $this->originalData = '';
            $this->originalTimestamp = 0;
            return '';
        } catch (Exception $e) {
            return '';
        }
    }

    public function write($id, $data): bool {
        try {
            $currentTime = time();
            // Optimize: skip writing if session data hasn't changed and the session is less than 5 minutes old
            if ($data === $this->originalData && $this->originalTimestamp > 0 && ($currentTime - $this->originalTimestamp) < 300) {
                return true;
            }

            $stmt = $this->pdo->prepare("REPLACE INTO `sessions` (`id`, `data`, `timestamp`) VALUES (:id, :data, :timestamp)");
            return $stmt->execute([
                ':id' => $id,
                ':data' => $data,
                ':timestamp' => $currentTime
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
