-- 
-- ITECH3108 Assignment 1
-- ZHI ZAO ONG
-- 30360914
-- 
-- 
-- Drop Database if exists
DROP DATABASE IF EXISTS `itech3108_30360914_a1`;
-- 
-- 
-- Create Database
CREATE DATABASE `itech3108_30360914_a1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `itech3108_30360914_a1`;
-- 
-- 
-- Drop Tables if exists
DROP TABLE IF EXISTS `user`,
`violin`,
`offer`,
`message`;
-- 
-- 
-- Create Tables
CREATE TABLE `user` (
  `id` SERIAL,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255),
  PRIMARY KEY (`id`)
);
CREATE TABLE `violin` (
  `id` SERIAL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `seeking` TEXT,
  `submitted` TIMESTAMP DEFAULT NOW(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);
CREATE TABLE `offer` (
  `id` SERIAL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `violin_id` BIGINT UNSIGNED NOT NULL,
  `offer` TEXT NOT NULL,
  `accepted` TIMESTAMP NULL DEFAULT NULL,
  `submitted` TIMESTAMP DEFAULT NOW(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
  FOREIGN KEY (`violin_id`) REFERENCES `violin`(`id`)
);
CREATE TABLE `message` (
  `id` SERIAL,
  `from_user_id` BIGINT UNSIGNED NOT NULL,
  `to_user_id` BIGINT UNSIGNED NOT NULL,
  `offer_id` BIGINT UNSIGNED NOT NULL,
  `sent` TIMESTAMP DEFAULT NOW(),
  `text` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`from_user_id`) REFERENCES `user`(`id`),
  FOREIGN KEY (`to_user_id`) REFERENCES `user`(`id`),
  FOREIGN KEY (`offer_id`) REFERENCES `offer`(`id`)
);
-- 
-- 
-- INSERT ROW
-- 
-- 
-- User Table
INSERT INTO `user` (
    `name`,
    `username`,
    `email`,
    `password`,
    `location`
  )
VALUES (
    'Zhi Zao Ong',
    '30360914',
    'zhizaoong@students.federation.edu.au',
    '$2y$10$123419063030000999999ubIYLh222lwfXDlCqWB9GZqOj6FqJEke',
    'Mount Helen'
  ),
  (
    'Tutor',
    'tutor',
    'tutor@federation.edu.au',
    '$2y$10$123419063030000999999ubIYLh222lwfXDlCqWB9GZqOj6FqJEke',
    'Ballarat'
  ),
  (
    'Carree Grunwald',
    'cgrundwald',
    'cgrundwald@gmail.com',
    '$2y$10$123419063030000999999ubIYLh222lwfXDlCqWB9GZqOj6FqJEke',
    'Melbourne'
  ),
  (
    'Elsa Watting',
    'ewatting',
    'ewatting@gmail.com',
    '$2y$10$123419063030000999999ubIYLh222lwfXDlCqWB9GZqOj6FqJEke',
    'Ballarat'
  ),
  (
    'De Treffrey',
    'dtreffy',
    'dtreffy@gmail.com',
    '$2y$10$123419063030000999999ubIYLh222lwfXDlCqWB9GZqOj6FqJEke',
    'Melbourne'
  );
-- 
-- 
-- Violin Table
INSERT INTO `violin` (
    `user_id`,
    `title`,
    `description`,
    `seeking`,
    `submitted`
  )
VALUES (
    1,
    'Enrico Student Plus',
    'Good condition. Comes with a bow. 1 year Warranty',
    'Selling for AU$350',
    '2020-12-10 09:00:00'
  ),
  (
    2,
    'Antique German Violin',
    'Beautiful old German trade violin. Great tone and professionally set up.',
    'Willing to exchange, or sell for $AU500',
    '2020-12-10 10:00:00'
  ),
  (
    3,
    'Arioso Violin Outfit',
    'Hand-crafted to last and sound spectacular. #1 recommended by teachers.',
    'Accepting AU$350. Price is negotiable.',
    '2020-12-10 15:00:00'
  );
-- 
-- 
-- Offer Table
-- Accepted
INSERT INTO `offer` 
  (`user_id`, `violin_id`, `offer`, `accepted`, `submitted`)
VALUES 
  (2, 1, 'I am willing to pay AU$300.', '2020-12-14 10:30:00', '2020-12-13 12:00:00');
--  Not yet accepted
INSERT INTO `offer` 
  (`user_id`, `violin_id`, `offer`, `submitted`)
VALUES 
  (4, 1, 'I can pay AU$290.', '2020-12-15 14:20:00'),
  (1, 3, 'I would like to make an exchange.', '2020-12-15 09:45:00'),
  (5, 3, 'Would you accept AU$310?', '2020-12-16 15:10:00'),
  (1, 2, 'I will pay AU$420', '2020-12-18 20:30:00');
-- 
-- 
-- Message Table
INSERT INTO `message` (
    `id`,
    `from_user_id`,
    `to_user_id`,
    `offer_id`,
    `sent`,
    `text`
  )
VALUES 
  (1, 1, 1, 1, '2020-12-15 03:37:17', 'Hello'),
  (2, 2, 1, 1, '2020-12-15 03:38:48', 'Hi, would you deliver the violin to my address?'),
  (3, 1, 1, 1, '2020-12-15 03:39:08', 'Yes, please give me your address.'),
  (4, 2, 1, 1, '2020-12-15 03:39:34', '1 University Drive, Mount Helen 3350 VIC');