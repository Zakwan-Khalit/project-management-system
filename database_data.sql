-- Database Data: Insert Statements Only

-- Insert status lookup data
INSERT INTO `status_lookup` (`type`, `code`, `name`, `description`, `color`, `order_index`) VALUES
-- Project statuses
('project', 'planning', 'Planning', 'Project is in planning phase', '#6c757d', 1),
('project', 'active', 'Active', 'Project is currently active', '#28a745', 2),
('project', 'on_hold', 'On Hold', 'Project is temporarily on hold', '#ffc107', 3),
('project', 'completed', 'Completed', 'Project has been completed', '#007bff', 4),
('project', 'cancelled', 'Cancelled', 'Project has been cancelled', '#dc3545', 5),
-- Task statuses
('task', 'todo', 'To Do', 'Task is pending', '#6c757d', 1),
('task', 'in_progress', 'In Progress', 'Task is being worked on', '#17a2b8', 2),
('task', 'review', 'In Review', 'Task is under review', '#ffc107', 3),
('task', 'completed', 'Completed', 'Task has been completed', '#28a745', 4),
('task', 'cancelled', 'Cancelled', 'Task has been cancelled', '#dc3545', 5),
-- User statuses
('user', 'active', 'Active', 'User is active', '#28a745', 1),
('user', 'inactive', 'Inactive', 'User is inactive', '#6c757d', 2),
('user', 'suspended', 'Suspended', 'User is suspended', '#dc3545', 3);

-- Insert priority lookup data
INSERT INTO `priority_lookup` (`type`, `code`, `name`, `description`, `color`, `level`, `order_index`) VALUES
-- Project priorities
('project', 'low', 'Low', 'Low priority project', '#28a745', 1, 1),
('project', 'medium', 'Medium', 'Medium priority project', '#ffc107', 2, 2),
('project', 'high', 'High', 'High priority project', '#fd7e14', 3, 3),
('project', 'urgent', 'Urgent', 'Urgent priority project', '#dc3545', 4, 4),
-- Task priorities
('task', 'low', 'Low', 'Low priority task', '#28a745', 1, 1),
('task', 'medium', 'Medium', 'Medium priority task', '#ffc107', 2, 2),
('task', 'high', 'High', 'High priority task', '#fd7e14', 3, 3),
('task', 'urgent', 'Urgent', 'Urgent priority task', '#dc3545', 4, 4);

-- Insert department lookup data
INSERT INTO `department_lookup` (`code`, `name`, `description`) VALUES
('IT', 'Information Technology', 'Information Technology Department'),
('HR', 'Human Resources', 'Human Resources Department'),
('FIN', 'Finance', 'Finance Department'),
('MKT', 'Marketing', 'Marketing Department'),
('OPS', 'Operations', 'Operations Department'),
('DEV', 'Development', 'Software Development Department'),
('QA', 'Quality Assurance', 'Quality Assurance Department'),
('PM', 'Project Management', 'Project Management Office');

-- Insert position lookup data
INSERT INTO `position_lookup` (`code`, `name`, `description`, `level`) VALUES
('CEO', 'Chief Executive Officer', 'Chief Executive Officer', 10),
('CTO', 'Chief Technology Officer', 'Chief Technology Officer', 9),
('VP_ENG', 'VP Engineering', 'Vice President of Engineering', 8),
('DIR_DEV', 'Director of Development', 'Director of Development', 7),
('SR_PM', 'Senior Project Manager', 'Senior Project Manager', 6),
('PM', 'Project Manager', 'Project Manager', 5),
('JR_PM', 'Junior Project Manager', 'Junior Project Manager', 4),
('SR_DEV', 'Senior Developer', 'Senior Software Developer', 6),
('DEV', 'Developer', 'Software Developer', 4),
('JR_DEV', 'Junior Developer', 'Junior Software Developer', 3),
('SR_ANALYST', 'Senior System Analyst', 'Senior System Analyst', 5),
('ANALYST', 'System Analyst', 'System Analyst', 4),
('QA_LEAD', 'QA Lead', 'Quality Assurance Lead', 5),
('QA_ENG', 'QA Engineer', 'Quality Assurance Engineer', 4);

-- Insert user role lookup data
INSERT INTO `user_role_lookup` (`code`, `name`, `description`, `permissions`, `level`) VALUES
('superadmin', 'Super Administrator', 'Super Administrator with full system access', '{"all": true, "system": ["full_access"], "users": ["create", "read", "update", "delete"], "roles": ["manage"]}', 10),
('admin', 'Administrator', 'Administrator with user and project management access', '{"users": ["create", "read", "update", "delete"], "projects": ["create", "read", "update", "delete"], "tasks": ["create", "read", "update", "delete"], "reports": ["read"]}', 8),
('project_manager', 'Project Manager', 'Project Manager with project and task management access', '{"projects": ["create", "read", "update", "delete"], "tasks": ["create", "read", "update", "delete"], "users": ["read"], "reports": ["read"]}', 6),
('project_executive', 'Project Executive', 'Project Executive with project oversight and reporting access', '{"projects": ["read", "update"], "tasks": ["read", "update"], "reports": ["read"], "budget": ["view"]}', 7),
('system_analyst', 'System Analyst', 'System Analyst with requirements and analysis access', '{"projects": ["read"], "tasks": ["create", "read", "update"], "requirements": ["create", "read", "update"], "reports": ["read"]}', 5),
('developer', 'Developer', 'Developer with task execution and code management access', '{"projects": ["read"], "tasks": ["read", "update"], "own_tasks": ["create", "update", "delete"], "code": ["commit"]}', 4);

-- Insert default superadmin user (password: admin123)
INSERT INTO `users` (`email`, `password`, `is_active`, `email_verified_at`) VALUES
('admin@projectmanagement.local', '$2y$10$52N2nJ9b3o/bhozS/staJuACX6T4sKFtp8UPdLw.JqDF8E.Yb.cza', 1, NOW());

-- Insert user profile for admin
INSERT INTO `user_profile` (`user_id`, `first_name`, `last_name`, `phone`, `bio`, `timezone`) VALUES
(1, 'Super', 'Admin', '+1234567890', 'System Administrator with full access', 'America/New_York');

-- Assign superadmin role to the user
INSERT INTO `user_role` (`user_id`, `role_id`, `assigned_by`) VALUES
(1, 1, 1);

-- Insert sample project
INSERT INTO `projects` (`name`, `code`, `description`, `budget`, `start_date`, `end_date`) VALUES
('Project Management System', 'PMS-2025-001', 'A comprehensive project management system with task tracking, team collaboration, and progress monitoring features.', 50000.00, '2025-01-01', '2025-06-30');

-- Set project status to active
INSERT INTO `project_status` (`project_id`, `status_id`, `changed_by`, `notes`) VALUES
(1, 2, 1, 'Project started and is now active');

-- Set project priority to high
INSERT INTO `project_priority` (`project_id`, `priority_id`, `changed_by`, `notes`) VALUES
(1, 3, 1, 'High priority project for company growth');

-- Add admin to the sample project as manager
INSERT INTO `project_members` (`project_id`, `user_id`, `role`, `assigned_by`) VALUES
(1, 1, 'manager', 1);

-- Add project client information
INSERT INTO `project_client` (`project_id`, `client_name`, `client_email`, `client_phone`, `contact_person`, `contract_value`) VALUES
(1, 'Tech Solutions Inc.', 'contact@techsolutions.com', '+1-555-123-4567', 'John Smith', 50000.00);

-- Insert task templates with header IDs (see task_headers table for correct IDs)
INSERT INTO `task_templates` (`id`, `code`, `project_id`, `name`, `description`, `fields`) VALUES
(1, 'brs', '1', 'Business Requirement Specification', 'BRS Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]'),
(2, 'uat', '1', 'User Acceptance Testing', 'UAT Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]'),
(3, 'fat', '1', 'Factory Acceptance Testing', 'FAT Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]');

INSERT INTO `tasks` (`project_id`, `template_id`, `data`, `is_active`, `is_delete`, `task_order`, `date_created`, `date_modified`) VALUES
 (1, 2, '{"1":"Login","2":"Ali","3":"Frontend Developer","4":"UI needs improvement","5":"Login Button","6":"","7":"Ali","8":"in_progress","9":60,"10":"UI needs improvement","11":"2025-01-02","12":"2025-01-05","13":"2025-01-05"}', 1, 0, 1, NOW(), NOW()),
 (1, 2, '{"1":"Register","2":"Sara","3":"Backend Developer","4":"API pending","5":"Register API","6":"","7":"Sara","8":"todo","9":0,"10":"API pending","11":"2025-01-03","12":"2025-01-10","13":"2025-01-10"}', 1, 0, 2, NOW(), NOW()),
 (1, 1, '{"1":"Requirements","2":"Ali","3":"System Analyst","4":"","5":"Initial requirements","6":"","7":"Ali","8":"planning","9":0,"10":"Requirements gathering phase","11":"2025-01-02","12":"2025-01-02","13":"2025-01-02"}', 1, 0, 3, NOW(), NOW()),
 (1, 3, '{"1":"Factory Test","2":"Sara","3":"QA Engineer","4":"","5":"Test login flow","6":"","7":"Sara","8":"todo","9":0,"10":"Factory test case","11":"2025-01-05","12":"2025-01-05","13":"2025-01-05"}', 1, 0, 4, NOW(), NOW()),
 (1, 2, '{"1":"Login","2":"Siti","3":"Backend Developer","4":"Pending API integration","5":"Authentication API","6":"","7":"Siti","8":"todo","9":0,"10":"Pending API integration","11":"2025-01-02","12":"2025-01-06","13":"2025-01-06"}', 1, 0, 5, NOW(), NOW()),
 (1, 2, '{"1":"Dashboard","2":"John","3":"Frontend Developer","4":"Awaiting feedback","5":"Progress Chart","6":"","7":"John","8":"review","9":80,"10":"Awaiting feedback","11":"2025-01-10","12":"2025-01-15","13":"2025-01-15"}', 1, 0, 6, NOW(), NOW());

-- Set task statuses
INSERT INTO `task_status` (`task_id`, `status_id`, `changed_by`, `notes`) VALUES
(1, 7, 1, 'Task completed successfully');

-- Sample data for task_images
INSERT INTO `task_images` (`task_id`, `file_name`, `file_address`, `is_active`, `is_delete`, `date_created`, `date_modified`) VALUES
  (1, 'sample1.png', 'task_image/sample1.png', 1, 0, NOW(), NOW()),
  (2, 'sample2.jpg', 'task_image/sample2.jpg', 1, 0, NOW(), NOW());


-- Insert header names into header_lookup for dropdown use
INSERT INTO `header_lookup` (`column_name`) VALUES
('Module'),
('Tester Name'),
('Role'),
('Issue'),
('Description'),
('Image'),
('PIC'),
('Status'),
('Progress'),
('Notes'),
('Start Date'),
('End Date'),
('Last Modified');

-- Insert sample task headers (unchanged)
INSERT INTO `task_headers` (`column_name`, `is_active`, `is_delete`) VALUES
('Module', 1, 0),
('Tester Name', 1, 0),
('Role', 1, 0),
('Issue', 1, 0),
('Description', 1, 0),
('Image', 1, 0),
('PIC', 1, 0),
('Status', 1, 0),
('Progress', 1, 0),
('Notes', 1, 0),
('Start Date', 1, 0),
('End Date', 1, 0),
('Last Modified', 1, 0);

-- Update task_templates fields column to use header IDs (example: [1,2,3,...])
UPDATE `task_templates` SET `fields` = '[1,2,3,4,5,6,7,8,9,10,11,12,13]' WHERE `id` = 1;
UPDATE `task_templates` SET `fields` = '[1,2,3,4,5,6,7,8,9,10,11,12,13]' WHERE `id` = 2;
UPDATE `task_templates` SET `fields` = '[1,2,3,4,5,6,7,8,9,10,11,12,13]' WHERE `id` = 3;
