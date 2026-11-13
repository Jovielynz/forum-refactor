
<?php ob_start(); ?>

<h2>Write a Comment or Reply</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form action="index.php?action=reply" method="POST">

    <input
        type="hidden"
        name="discussion_id"
        value="<?= (int) $discussionId ?>"
    >

    <input
        type="hidden"
        name="parent_id"
        value="<?= $parentId === null ? '' : (int) $parentId ?>"
    >

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken ?? '') ?>"
    >

    <div style="margin-bottom: 15px;">
        <label for="body">Your Comment:</label><br>

        <textarea
            id="body"
            name="body"
            rows="5"
            maxlength="2000"
            required
            style="width: 100%; padding: 10px;"
        ></textarea>
    </div>

    <button type="submit">Post Comment</button>

</form>

<p>
    <a href="index.php?action=show&id=<?= (int) $discussionId ?>">
        Cancel and go back
    </a>
</p>

<?php
$content = ob_get_clean();
$pageTitle = 'Write a Reply';

require __DIR__ . '/layout.php';
?>
