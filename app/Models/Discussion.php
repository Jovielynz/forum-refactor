
<?php

class Discussion
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = '
            SELECT
                discussions.id,
                discussions.title,
                discussions.body,
                discussions.created_at,
                users.username
            FROM discussions
            INNER JOIN users
                ON discussions.user_id = users.id
            ORDER BY discussions.created_at DESC
        ';

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                discussions.id,
                discussions.title,
                discussions.body,
                discussions.created_at,
                users.username
             FROM discussions
             INNER JOIN users
                ON discussions.user_id = users.id
             WHERE discussions.id = :id'
        );

        $stmt->execute(['id' => $id]);

        $discussion = $stmt->fetch();

        return $discussion ?: null;
    }
}
