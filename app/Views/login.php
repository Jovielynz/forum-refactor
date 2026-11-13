
<?php ob_start(); ?>

<h2>Login to Your Account</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form action="index.php?action=login" method="POST">

    <div style="margin-bottom: 15px;">
        <label for="email">Email Address:</label><br>

        <input
            type="email"
            id="email"
            name="email"
            required
            style="width: 100%; padding: 10px;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="password">Password:</label><br>

        <input
            type="password"
            id="password"
            name="password"
            required
            style="width: 100%; padding: 10px;"
        >
    </div>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken ?? '') ?>"
    >

    <button type="submit">Login</button>

</form>

<p>
    Don't have an account?
    <a href="index.php?action=register">Register here</a>
</p>

<?php
$content = ob_get_clean();
$pageTitle = 'Login';

require __DIR__ . '/layout.php';
?>
