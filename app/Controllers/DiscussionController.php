
<?php

require_once __DIR__ . '/../Models/Discussion.php';
require_once __DIR__ . '/../Models/Comment.php';

class DiscussionController
{
    private Discussion $discussionModel;
    private Comment $commentModel;

    public function __construct(PDO $pdo)
    {
        $this->discussionModel = new Discussion($pdo);
        $this->commentModel = new Comment($pdo);
    }

    public function index(): array
    {
        return $this->discussionModel->getAll();
    }

    public function show(int $id): ?array
    {
        $discussion = $this->discussionModel->findById($id);

        if (!$discussion) {
            return null;
        }

        $comments = $this->commentModel
            ->getByDiscussionId($id);

        return [
            'discussion' => $discussion,
            'comments' => $comments
        ];
    }
}
