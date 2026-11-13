
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($pageTitle ?? 'My Forum') ?>
    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background: #263238;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        a {
            color: #1976d2;
            text-decoration: none;
        }

        button {
            padding: 10px 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>


<header>
    <h1>My Discussion Forum</h1>
    <p>Share ideas and join conversations.</p>

    <nav>
        <a href="index.php" style="color: white; margin: 10px;">
            Home
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <span>
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
            </span>

            <form action="index.php?action=logout" method="POST"
                  style="display: inline;">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>"
                >

                <button type="submit">Logout</button>
            </form>

        <?php else: ?>

            <a href="index.php?action=login"
               style="color: white; margin: 10px;">
                Login
            </a>

            <a href="index.php?action=register"
               style="color: white; margin: 10px;">
                Register
            </a>

        <?php endif; ?>
    </nav>
</header>


<main>
    <?= $content ?? '' ?>
</main>

</body>
</html>
