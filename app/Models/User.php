
<?php

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, username, email, created_at
             FROM users
             WHERE id = :id'
        );

        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM users
             WHERE email = :email'
        );

        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}
