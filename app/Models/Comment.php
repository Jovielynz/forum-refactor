
<?php

class Comment
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByDiscussionId(int $discussionId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                comments.id,
                comments.discussion_id,
                comments.parent_id,
                comments.user_id,
                comments.body,
                comments.created_at,
                users.username
             FROM comments
             INNER JOIN users
                ON comments.user_id = users.id
             WHERE comments.discussion_id = :discussion_id
             ORDER BY comments.created_at ASC, comments.id ASC'
        );

        $stmt->execute([
            'discussion_id' => $discussionId
        ]);

        return $stmt->fetchAll();
    }

    public function create(
        int $discussionId,
        ?int $parentId,
        int $userId,
        string $body
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comments
                (discussion_id, parent_id, user_id, body)
             VALUES
                (:discussion_id, :parent_id, :user_id, :body)'
        );

        return $stmt->execute([
            'discussion_id' => $discussionId,
            'parent_id' => $parentId,
            'user_id' => $userId,
            'body' => $body
        ]);
    }
}
