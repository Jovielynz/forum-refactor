CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE discussions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_discussion_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    discussion_id INT UNSIGNED NOT NULL,
    parent_id INT UNSIGNED NULL,
    user_id INT UNSIGNED NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_discussion_comment (discussion_id, id),

    CONSTRAINT fk_comment_discussion
        FOREIGN KEY (discussion_id)
        REFERENCES discussions(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comment_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comment_parent
        FOREIGN KEY (discussion_id, parent_id)
        REFERENCES comments(discussion_id, id)
        ON DELETE CASCADE
) ENGINE=InnoDB;



INSERT INTO users (username, email, password_hash)
VALUES
('Alice', 'alice@example.com', NULL),
('Ben', 'ben@example.com', NULL),
('Cara', 'cara@example.com', NULL),
('Dee', 'dee@example.com', NULL),
('Eli', 'eli@example.com', NULL);

INSERT INTO discussions (user_id, title, body)
VALUES (
    1,
    'How do I learn PHP?',
    'Share your learning tips.'
);

INSERT INTO comments
(discussion_id, parent_id, user_id, body)
VALUES
(1, NULL, 1, 'Start with the PHP manual.'),
(1, 1,    2, 'Try the examples as you read.'),
(1, 2,    3, 'Build a small project too.'),
(1, 1,    4, 'Which manual section first?'),
(1, NULL, 5, 'Practice SQL as well.'),
(1, 3,    2, 'Start with a forum.'),
(1, 6,    5, 'Add replies next.');

