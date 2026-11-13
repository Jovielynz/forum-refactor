
<?php

require_once __DIR__ . '/../Models/Comment.php';
require_once __DIR__ . '/../Models/Discussion.php';

class CommentController
{
    private Comment $commentModel;
    private Discussion $discussionModel;

    public function __construct(PDO $pdo)
    {
        $this->commentModel = new Comment($pdo);
        $this->discussionModel = new Discussion($pdo);
    }

    public function store(
        int $discussionId,
        ?int $parentId,
        string $body
    ): bool {

        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $body = trim($body);

        if ($body === '' || strlen($body) > 2000) {
            return false;
        }

        $discussion = $this->discussionModel
            ->findById($discussionId);

        if (!$discussion) {
            return false;
        }

        // Check the parent comment and reply depth
        if ($parentId !== null) {

            $comments = $this->commentModel
                ->getByDiscussionId($discussionId);

            $commentMap = [];

            foreach ($comments as $comment) {
                $commentMap[(int) $comment['id']] =
                    $comment['parent_id'] === null
                        ? null
                        : (int) $comment['parent_id'];
            }

            $currentId = $parentId;
            $parentDepth = 0;

            while ($currentId !== null) {

                if (!array_key_exists($currentId, $commentMap)) {
                    return false;
                }

                $parentDepth++;

                // Maximum allowed comment depth is 10
                if ($parentDepth >= 10) {
                return false;
}

                $currentId = $commentMap[$currentId];
            }
        }

        return $this->commentModel->create(
            $discussionId,
            $parentId,
            (int) $_SESSION['user_id'],
            $body
        );
    }
}
