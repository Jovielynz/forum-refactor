
<?php

require_once __DIR__ . '/../Models/User.php';

class AuthController
{
    private User $userModel;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
    }

    public function register(
        string $username,
        string $email,
        string $password
    ): bool {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $this->pdo->prepare(
            'INSERT INTO users
                (username, email, password_hash)
             VALUES
                (:username, :email, :password_hash)'
        );

        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userModel->findByEmail($email);

        if (!$user || empty($user['password_hash'])) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        return true;
    }

    
public function logout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite']
            ]
        );
    }

    session_destroy();
}

}
