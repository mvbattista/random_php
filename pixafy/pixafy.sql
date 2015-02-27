CREATE DATABASE pixafy;

CREATE TABLE users (
  id serial primary key,
  email VARCHAR(64) NOT NULL,
  password VARCHAR(32) NOT NULL,
  is_admin INTEGER NOT NULL DEFAULT 0
);

ALTER TABLE users ADD UNIQUE INDEX (email);

CREATE TABLE images (
id serial primary key,
user_id BIGINT UNSIGNED NOT NULL,
file_location VARCHAR(255) NOT NULL
);

ALTER TABLE images ADD UNIQUE INDEX (file_location);

ALTER TABLE images
ADD CONSTRAINT user_image
FOREIGN KEY (user_id) REFERENCES users(id);