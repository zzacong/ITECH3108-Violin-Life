-- 
-- ITECH3108 Assignment 1
-- ZHI ZAO ONG
-- 30360914
-- 
-- 
-- Drop Database if exists
DROP DATABASE IF EXISTS `itech3108_30360914_a1`;
-- 
-- Create Database
CREATE DATABASE `itech3108_30360914_a1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `itech3108_30360914_a1`;
-- 
-- Drop Tables if exists
DROP TABLE IF EXISTS `user`,
`violin`,
`offer`,
`message`;
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
  `description` VARCHAR(255),
  `seeking` VARCHAR(255),
  `submitted` TIMESTAMP DEFAULT NOW(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);
CREATE TABLE `offer` (
  `id` SERIAL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `violin_id` BIGINT UNSIGNED NOT NULL,
  `offer` VARCHAR(255) NOT NULL,
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
  `text` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`from_user_id`) REFERENCES `user`(`id`),
  FOREIGN KEY (`to_user_id`) REFERENCES `user`(`id`),
  FOREIGN KEY (`offer_id`) REFERENCES `offer`(`id`)
);