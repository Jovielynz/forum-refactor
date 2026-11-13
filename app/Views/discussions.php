
<?php ob_start(); ?>

<h2>All Discussions</h2>

<?php if (empty($discussions)): ?>

    <p>No discussions available yet.</p>

<?php else: ?>

    <?php foreach ($discussions as $discussion): ?>

        <div style="
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        ">

            <h3>
                <?= htmlspecialchars($discussion['title']) ?>
            </h3>

            <p>
                <?= htmlspecialchars($discussion['body']) ?>
            </p>

            <p>
                Posted by:
                <?= htmlspecialchars($discussion['username']) ?>
            </p>

            <a href="index.php?action=show&id=<?= (int) $discussion['id'] ?>">
                View Discussion
            </a>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
$pageTitle = 'All Discussions';

require __DIR__ . '/layout.php';
?>
