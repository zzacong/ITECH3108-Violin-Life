-- 
-- ITECH3108 Assignment 1
-- ZHI ZAO ONG
-- 30360914
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
    '$2y$10$cQaXmxx63ZsGBBR7EGLYpOCwBBN2FAmg49X3Hl9Gz/cEVC3N/H5Gi',
    'Mount Helen'
  ),
  (
    'The Tutor',
    'tutor',
    'tutor@federation.edu.au',
    '$2y$10$j8Gqs/pg79oGwzwDVIkRgu/.d15BPFKerxOevC08Lx82AMzrVBnce',
    'Ballarat'
  ),
  (
    'Carree Grunwald',
    'cgrundwald',
    'cgrundwald@gmail.com',
    '$2y$10$cMA0ncbXyir/HIWsfla.PuqhH6WqzBirGzyzTLOU5UFXeoJQ4VeEu',
    'Melbourne'
  ),
  (
    'Elsa Watting',
    'ewatting',
    'ewatting@gmail.com',
    '$2y$10$v8EwK30.nAiIzEo9Y7P40eXynPhPAD.ClszMlWXtZW/.a.v55zueW',
    'Ballarat'
  ),
  (
    'De Treffrey',
    'dtreffy',
    'dtreffy@gmail.com',
    '$2y$10$XlRF1yZ4pPSrSCHbDrkKsuVj6UnSDaTAz8.g1TiGw9USVGyg0qAhW',
    'Melbourne'
  );
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
-- Offer Table
INSERT INTO `offer` (
    `user_id`,
    `violin_id`,
    `offer`,
    `accepted`,
    `submitted`
  )
VALUES (
    2,
    1,
    'I am willing to pay AU$300.',
    '2020-12-14 10:30:00',
    '2020-12-13 12:00:00'
  );
--  Not yet accepted
INSERT INTO `offer` (`user_id`, `violin_id`, `offer`, `submitted`)
VALUES (4, 1, 'I can pay AU$290.', '2020-12-15 14:20:00'),
  (
    1,
    3,
    'I would like to make an exchange.',
    '2020-12-15 09:45:00'
  ),
  (
    5,
    3,
    'Would you accept AU$310?',
    '2020-12-16 15:10:00'
  );