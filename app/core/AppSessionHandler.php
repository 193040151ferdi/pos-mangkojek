<?php

class AppSessionHandler implements SessionHandlerInterface {
    private $cookieName = 'POS_SESS';
    private $key;
    private $originalData = '';

    public function __construct() {
        // Use database password and name as the secret key for encryption
        $this->key = hash('sha256', DB_PASS . DB_NAME, true);
    }

    public function open($savePath, $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string {
        if (isset($_COOKIE[$this->cookieName])) {
            $encryptedData = $_COOKIE[$this->cookieName];
            $decrypted = $this->decrypt($encryptedData);
            if ($decrypted !== false) {
                $this->originalData = $decrypted;
                return $decrypted;
            }
        }
        $this->originalData = '';
        return '';
    }

    public function write($id, $data): bool {
        // Skip writing if the data has not changed
        if ($data === $this->originalData) {
            return true;
        }

        $encrypted = $this->encrypt($data);
        
        // Set cookie (expire in 1 day, secure, httponly, samesite Lax)
        setcookie($this->cookieName, $encrypted, [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        return true;
    }

    public function destroy($id): bool {
        // Delete cookie by setting expiry in the past
        setcookie($this->cookieName, '', time() - 3600, '/');
        return true;
    }

    public function gc($maxLifetime): int {
        return 0;
    }

    private function encrypt($data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decrypt($data) {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        if (strlen($data) <= $ivLength) {
            return false;
        }
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $this->key, 0, $iv);
    }

    public static function register() {
        // Only use custom session handler on Vercel
        if (getenv('VERCEL') !== false || isset($_SERVER['VERCEL']) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false)) {
            $handler = new self();
            session_set_save_handler($handler, true);
        }
    }
}
