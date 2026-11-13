
<?php

function displayComments(array $comments, ?int $parentId = null, int $depth = 0): void
{
        
    if ($depth > 10) {
        return;
    }
    foreach ($comments as $comment) {

        $commentParentId = $comment['parent_id'] === null
    ? null
    : (int) $comment['parent_id'];

    if ($commentParentId !== $parentId) {
        continue;

        }


        $commentId = (int) $comment['id'];

        $indent = min($depth, 8) * 25;

        echo '<div style="
            margin-left: ' . $indent . 'px;
            border-left: 3px solid #ddd;
            padding: 10px;
            margin-top: 10px;
        ">';

        echo '<strong>' .
            htmlspecialchars($comment['username']) .
            '</strong>';

        echo '<p>' .
            htmlspecialchars($comment['body']) .
            '</p>';

        echo '<small>Depth: ' . $depth . '</small>';

        echo '<p>
            <a href="index.php?action=reply&discussion_id=' .
            (int) $comment['discussion_id'] .
            '&parent_id=' . $commentId .
            '">Reply</a>
        </p>';

        displayComments($comments, $commentId, $depth + 1);

        echo '</div>';
    }
}

ob_start();
?>

<h2><?= htmlspecialchars($discussion['title']) ?></h2>

<p><?= htmlspecialchars($discussion['body']) ?></p>

<p>
    Started by:
    <?= htmlspecialchars($discussion['username']) ?>
</p>

<hr>


<h3>Comments and Replies</h3>

<p>
    <a href="index.php?action=reply&discussion_id=<?= (int) $discussion['id'] ?>">
        + Write a Comment
    </a>
</p>

<?php displayComments($comments); ?>


<hr>

<p>
    <a href="index.php">Back to Discussions</a>
</p>

<?php
$content = ob_get_clean();
$pageTitle = $discussion['title'];

require __DIR__ . '/layout.php';
?>
