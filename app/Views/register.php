
<?php ob_start(); ?>

<h2>Create Your Account</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form action="index.php?action=register" method="POST">

    <div style="margin-bottom: 15px;">
        <label for="username">Username:</label><br>

        <input
            type="text"
            id="username"
            name="username"
            maxlength="50"
            required
            style="width: 100%; padding: 10px;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email">Email Address:</label><br>

        <input
            type="email"
            id="email"
            name="email"
            maxlength="150"
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
            minlength="8"
            required
            style="width: 100%; padding: 10px;"
        >
    </div>

    <div style="margin-bottom: 15px;">
        <label for="confirm_password">Confirm Password:</label><br>

        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            minlength="8"
            required
            style="width: 100%; padding: 10px;"
        >
    </div>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken ?? '') ?>"
    >

    <button type="submit">Register</button>

</form>

<p>
    Already have an account?
    <a href="index.php?action=login">Login here</a>
</p>

<?php
$content = ob_get_clean();
$pageTitle = 'Register';

require __DIR__ . '/layout.php';
?>
