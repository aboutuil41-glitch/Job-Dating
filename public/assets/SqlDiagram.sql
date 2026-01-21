CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100),
  `email` varchar(150) UNIQUE,
  `password` varchar(255),
  `role` enum('admin','student'),
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `students` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `first_name` varchar(100),
  `last_name` varchar(100),
  `email` varchar(150),
  `promotion` int,
  `specialization` varchar(150),
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `companies` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100),
  `sector` varchar(100),
  `location` varchar(100),
  `email` varchar(150) UNIQUE,
  `phone` varchar(20),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `announcements` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(100),
  `company_id` int,
  `contract_type` varchar(100),
  `description` varchar(255),
  `location` varchar(100),
  `skills` varchar(255),
  `deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

ALTER TABLE `students` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `announcements` ADD FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);
