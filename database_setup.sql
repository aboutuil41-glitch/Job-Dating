-- Create database
CREATE DATABASE IF NOT EXISTS job_dating;
USE job_dating;

-- Create tables
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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add foreign keys
ALTER TABLE `students` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `announcements` ADD FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

-- Insert sample data
INSERT INTO companies (name, sector, location, email, phone, created_at, updated_at) VALUES
('YouCode', 'Education', 'Safi', 'contact@youcode.ma', '0661234567', NOW(), NOW()),
('OCP Group', 'Mining', 'Casablanca', 'hr@ocpgroup.ma', '0522123456', NOW(), NOW()),
('Attijariwafa Bank', 'Banking', 'Rabat', 'jobs@attijariwafa.com', '0537654321', NOW(), NOW()),
('Maroc Telecom', 'Telecommunications', 'Rabat', 'careers@iam.ma', '0537111222', NOW(), NOW()),
('BMCE Bank', 'Banking', 'Casablanca', 'hr@bmcebank.ma', '0522333444', NOW(), NOW());

INSERT INTO announcements (title, company_id, contract_type, description, location, skills, deleted, created_at, updated_at) VALUES
('Full Stack Developer', 1, 'CDI', 'Develop web applications using modern technologies. Join our team to build innovative educational platforms.', 'Safi', 'PHP,JavaScript,MySQL,Laravel,Vue.js', 0, NOW(), NOW()),
('Data Analyst', 2, 'CDD', 'Analyze business data and create reports. Work with big data to optimize mining operations.', 'Casablanca', 'Python,SQL,Excel,Power BI,Tableau', 0, NOW(), NOW()),
('Frontend Developer', 3, 'Internship', 'Create responsive user interfaces for banking applications. Learn modern frontend technologies.', 'Rabat', 'React,CSS,HTML,TypeScript,Sass', 0, NOW(), NOW()),
('DevOps Engineer', 4, 'CDI', 'Manage cloud infrastructure and deployment pipelines. Ensure high availability of telecom services.', 'Rabat', 'Docker,Kubernetes,AWS,Jenkins,Linux', 0, NOW(), NOW()),
('Mobile Developer', 5, 'CDD', 'Develop mobile banking applications for iOS and Android platforms.', 'Casablanca', 'React Native,Swift,Kotlin,Firebase', 0, NOW(), NOW()),
('Backend Developer', 1, 'CDI', 'Build robust APIs and microservices for educational platforms.', 'Safi', 'Node.js,Express,MongoDB,Redis', 0, NOW(), NOW()),
('UI/UX Designer', 2, 'Freelance', 'Design intuitive interfaces for industrial applications.', 'Casablanca', 'Figma,Adobe XD,Sketch,Prototyping', 0, NOW(), NOW()),
('Cybersecurity Specialist', 3, 'CDI', 'Protect banking systems from cyber threats and ensure compliance.', 'Rabat', 'Penetration Testing,CISSP,Firewall,SIEM', 0, NOW(), NOW());

-- Insert sample users
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@jobdating.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('John Doe', 'john@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW()),
('Jane Smith', 'jane@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW());

-- Insert sample students
INSERT INTO students (user_id, first_name, last_name, email, promotion, specialization, created_at, updated_at) VALUES
(2, 'John', 'Doe', 'john@student.com', 2024, 'Full Stack Development', NOW(), NOW()),
(3, 'Jane', 'Smith', 'jane@student.com', 2024, 'Data Science', NOW(), NOW());