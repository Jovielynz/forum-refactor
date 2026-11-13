
<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS'])
]);

session_start();

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DiscussionController.php';
require_once __DIR__ . '/../app/Controllers/CommentController.php';

$pdo = getPDO();

$authController = new AuthController($pdo);
$discussionController = new DiscussionController($pdo);
$commentController = new CommentController($pdo);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

$action = $_GET['action'] ?? 'index';


// Homepage
if ($action === 'index') {

    $discussions = $discussionController->index();

    require __DIR__ . '/../app/Views/discussions.php';

    exit;
}


// Show discussion
if ($action === 'show') {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        exit('Invalid discussion ID.');
    }

    $data = $discussionController->show($id);

    if (!$data) {
        http_response_code(404);
        exit('Discussion not found.');
    }

    $discussion = $data['discussion'];
    $comments = $data['comments'];

    require __DIR__ . '/../app/Views/discussion.php';

    exit;
}


// Login
if ($action === 'login') {

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($csrfToken, $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Invalid security token.');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($authController->login($email, $password)) {
            header('Location: index.php');
            exit;
        }

        $error = 'Invalid email or password.';
    }

    require __DIR__ . '/../app/Views/login.php';

    exit;
}


// Registration
if ($action === 'register') {

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($csrfToken, $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Invalid security token.');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (
            $username === '' ||
            strlen($username) > 50 ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($email) > 150 ||
            strlen($password) < 8
        ) {

            $error = 'Please enter valid registration details. Password must be at least 8 characters.';

        } elseif ($password !== $confirmPassword) {

            $error = 'Passwords do not match. Please try again.';

        } else {

            try {

                if ($authController->register($username, $email, $password)) {
                    header('Location: index.php?action=login');
                    exit;
                }

                $error = 'Registration failed. Please try again.';

            } catch (PDOException $e) {

                $error = 'That email address may already be registered.';
            }
        }
    }

    require __DIR__ . '/../app/Views/register.php';

    exit;
}


// Comments and replies
if ($action === 'reply') {

    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($csrfToken, $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Invalid security token.');
        }

        $discussionId = filter_var(
            $_POST['discussion_id'] ?? '',
            FILTER_VALIDATE_INT
        );

        $rawParentId = $_POST['parent_id'] ?? '';

        $parentId = $rawParentId === ''
            ? null
            : filter_var($rawParentId, FILTER_VALIDATE_INT);

        $body = trim($_POST['body'] ?? '');

        if (
            !$discussionId ||
            $discussionId < 1 ||
            ($parentId !== null && (!$parentId || $parentId < 1))
        ) {
            $error = 'Invalid discussion or reply ID.';

        } else {

            $success = $commentController->store(
                $discussionId,
                $parentId,
                $body
            );

            if ($success) {
                header(
                    'Location: index.php?action=show&id=' . $discussionId
                );
                exit;
            }

            $error = 'Unable to post your comment. Please check your input.';
        }

    } else {

        $discussionId = filter_input(
            INPUT_GET,
            'discussion_id',
            FILTER_VALIDATE_INT
        );

        $rawParentId = $_GET['parent_id'] ?? '';

        $parentId = $rawParentId === ''
            ? null
            : filter_var($rawParentId, FILTER_VALIDATE_INT);
    }

    if (
        !$discussionId ||
        $discussionId < 1 ||
        ($parentId !== null && (!$parentId || $parentId < 1))
    ) {
        http_response_code(400);
        exit('Invalid discussion or reply ID.');
    }

    $data = $discussionController->show($discussionId);

    if (!$data) {
        http_response_code(404);
        exit('Discussion not found.');
    }

    if ($parentId !== null) {

        $parentExists = false;

        foreach ($data['comments'] as $comment) {

            if ((int) $comment['id'] === $parentId) {
                $parentExists = true;
                break;
            }
        }

        if (!$parentExists) {
            http_response_code(404);
            exit('Parent comment not found.');
        }
    }

    require __DIR__ . '/../app/Views/reply.php';

    exit;
}


// Logout
if ($action === 'logout') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed.');
    }

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($csrfToken, $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit('Invalid security token.');
    }

    $authController->logout();

    header('Location: index.php');
    exit;
}


// Unknown page
http_response_code(404);

echo '<h1>404 - Page Not Found</h1>';
echo '<p>The page you are looking for does not exist.</p>';
echo '<a href="index.php">Return to Homepage</a>';

exit;
