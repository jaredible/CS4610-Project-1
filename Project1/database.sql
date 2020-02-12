DROP DATABASE IF EXISTS mvc;
CREATE DATABASE mvc;
USE mvc;

CREATE TABLE posts (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(128) NOT NULL,
    content TEXT NOT NULL,
    created_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

INSERT INTO posts (title, content) VALUES
('First post', 'This is a really interesting post.'),
('Second post', 'This is a fascinating post!'),
('Third post', 'This is a very informative psot.');