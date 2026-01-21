-- Sample data for testing
INSERT INTO companies (name, sector, location, email, phone, created_at, updated_at) VALUES
('YouCode', 'Education', 'Safi', 'contact@youcode.ma', '0661234567', NOW(), NOW()),
('OCP Group', 'Mining', 'Casablanca', 'hr@ocpgroup.ma', '0522123456', NOW(), NOW()),
('Attijariwafa Bank', 'Banking', 'Rabat', 'jobs@attijariwafa.com', '0537654321', NOW(), NOW());

INSERT INTO announcements (title, company_id, contract_type, description, location, skills, deleted, created_at, updated_at) VALUES
('Full Stack Developer', 1, 'CDI', 'Develop web applications using modern technologies', 'Safi', 'PHP,JavaScript,MySQL', 0, NOW(), NOW()),
('Data Analyst', 2, 'CDD', 'Analyze business data and create reports', 'Casablanca', 'Python,SQL,Excel', 0, NOW(), NOW()),
('Frontend Developer', 3, 'Internship', 'Create responsive user interfaces', 'Rabat', 'React,CSS,HTML', 0, NOW(), NOW());