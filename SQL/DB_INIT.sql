CREATE DATABASE if not exists library;

CREATE TABLE if not exists library.users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    api_key VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE if not exists library.books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    text TEXT NOT NULL,
    del INT DEFAULT 0 NOT NULL
);

CREATE TABLE if not exists library.users_books (
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES library.users (user_id),
    FOREIGN KEY (book_id) REFERENCES library.books (book_id)
);

CREATE TABLE if not exists library.users_books_access (
    user_id INT NOT NULL,
    viewer_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES library.users (user_id),
    FOREIGN KEY (viewer_id) REFERENCES library.users (user_id)
);