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
('PMO', 'Project Management Office', 'Project Management Office Department'),
('USB', 'Usability', 'Usability Department'),
('SWE', 'Software Engineering', 'Software Engineering Department'),
('INF', 'Infrastructure', 'Infrastructure Department'),
('DATA', 'Data', 'Data Department'),
('FIN', 'Finance', 'Finance Department');

-- Insert position lookup data
INSERT INTO `position_lookup` (`code`, `name`, `description`, `level`, `department_id`) VALUES
('PM', 'Project Manager', 'Project Manager', 6, 1),     -- PMO
('PE', 'Project Executive', 'Project Executive', 7, 1), -- PMO
('SA', 'System Analyst', 'System Analyst', 5, 2),       -- Usability
('FE', 'Functional Engineer', 'Functional Engineer', 4, 2), -- Usability
('DEV', 'Developer', 'Software Developer', 4, 3),       -- Software Engineering
('SE', 'System Engineer', 'System Engineer', 5, 4),     -- Infrastructure
('DBA', 'Database Administrator', 'Database Administrator', 5, 5), -- Data
('DA', 'Data Analyst', 'Data Analyst', 4, 5),           -- Data
('FIN', 'Finance', 'Finance Specialist', 4, 6);         -- Finance

-- Insert user role lookup data (REMOVED - Using position_lookup instead)
-- User roles are now managed through position_lookup and department_lookup

-- Insert default superadmin user (password: admin123)
INSERT INTO `users` (`email`, `password`, `is_active`, `email_verified_at`) VALUES
('admin@projectmanagement.local', '$2y$10$52N2nJ9b3o/bhozS/staJuACX6T4sKFtp8UPdLw.JqDF8E.Yb.cza', 1, NOW()),
('analyst@zanko.com', '$2y$10$52N2nJ9b3o/bhozS/staJuACX6T4sKFtp8UPdLw.JqDF8E.Yb.cza', 1, NOW());

-- Insert user profile for admin
INSERT INTO `user_profile` (`user_id`, `full_name`, `phone`, `bio`, `timezone`) VALUES
(1, 'Super Admin', '+1234567890', 'System Administrator with full access', 'America/New_York'),
(2, 'Natasha Nazrin', '+1234567891', 'System Analyst specializing in requirements analysis', 'America/New_York');

-- Assign roles via position_lookup (REMOVED - Using user_rel instead)
-- User roles are now managed through user_rel linking to position_lookup and department_lookup

-- Link users to departments and positions via user_rel
INSERT INTO `user_rel` (`user_id`, `department_id`, `position_id`, `is_active`, `is_delete`) VALUES
(1, 1, 1, 1, 0), -- Super Admin -> PMO -> Project Manager
(2, 2, 3, 1, 0); -- Natasha -> Usability -> System Analyst

-- Insert sample project
INSERT INTO `projects` (`name`, `code`, `description`, `client`, `budget`, `start_date`, `end_date`) VALUES
('Project Management System', 'PMS-2025-001', 'A comprehensive project management system with task tracking, team collaboration, and progress monitoring features.', 'Internal Development', 50000.00, '2025-01-01', '2025-06-30');

-- Set project status to active
INSERT INTO `project_status` (`project_id`, `status_id`, `changed_by`, `notes`) VALUES
(1, 2, 1, 'Project started and is now active');

-- Set project priority to high
INSERT INTO `project_priority` (`project_id`, `priority_id`, `changed_by`, `notes`) VALUES
(1, 3, 1, 'High priority project for company growth');

-- Add admin to the sample project as manager
INSERT INTO `project_members` (`project_id`, `user_id`, `role`, `assigned_by`) VALUES
(1, 1, 'manager', 1),
(1, 2, 'analyst', 1);

-- Add project client information
INSERT INTO `project_client` (`project_id`, `client_name`, `client_email`, `client_phone`, `contact_person`, `contract_value`) VALUES
(1, 'Tech Solutions Inc.', 'contact@techsolutions.com', '+1-555-123-4567', 'John Smith', 50000.00);

-- Insert project scopes
INSERT INTO `project_scopes` (`id`, `project_id`, `name`, `description`, `scope_order`) VALUES
(1, 1, 'System Analysis', 'Business requirement analysis and system design', 1),
(2, 1, 'Quality Assurance', 'Testing and quality assurance activities', 2),
(3, 1, 'Development', 'Software development and implementation', 3);

-- Insert task templates with scopes
INSERT INTO `task_templates` (`id`, `project_id`, `scope_id`, `name`, `description`, `fields`, `component_order`, `weightage`) VALUES
(1, 1, 1, 'Business Requirement Specification', 'BRS Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]', 1, 25.50),
(2, 1, 2, 'User Acceptance Testing', 'UAT Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]', 1, 35.75),
(3, 1, 2, 'Factory Acceptance Testing', 'FAT Template', '[1,2,3,4,5,6,7,8,9,10,11,12,13]', 2, 38.75);

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

-- Insert sample activity log data
INSERT INTO `activity_logs` (`user_id`, `project_id`, `action`, `description`, `details`, `is_active`, `is_delete`, `date_created`, `date_modified`) VALUES
(1, 1, 'created', 'Project created', 'New project "Project Management System" has been created', 1, 0, NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(1, 1, 'updated', 'Project updated', 'Project details have been modified', 1, 0, NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),
(1, 1, 'team_member_added', 'Team member added', 'Natasha Nazrin has been added to the project team', 1, 0, NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),
(1, 1, 'task_created', 'Task created', 'New task "User Acceptance Testing" has been created', 1, 0, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(2, 1, 'task_updated', 'Task progress updated', 'Task progress updated to 60%', 1, 0, NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),
(2, 1, 'comment_added', 'Comment added', 'New comment added to task discussion', 1, 0, NOW() - INTERVAL 12 HOUR, NOW() - INTERVAL 12 HOUR),
(1, 1, 'status_changed', 'Project status changed', 'Project status changed from Planning to Active', 1, 0, NOW() - INTERVAL 6 HOUR, NOW() - INTERVAL 6 HOUR),
(2, 1, 'task_created', 'Task created', 'New task "Business Requirement Specification" has been created', 1, 0, NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR),
(1, 1, 'task_updated', 'Task progress updated', 'Task "Dashboard" progress updated to 80%', 1, 0, NOW() - INTERVAL 1 HOUR, NOW() - INTERVAL 1 HOUR),
(2, 1, 'file_uploaded', 'File uploaded', 'Document "sample1.png" uploaded to project', 1, 0, NOW() - INTERVAL 30 MINUTE, NOW() - INTERVAL 30 MINUTE);

-- Events Module Data

-- Insert event types into status_lookup
INSERT INTO `status_lookup` (`type`, `code`, `name`, `description`, `color`, `order_index`) VALUES
('event', 'meeting', 'Meeting', 'Team or client meeting', '#3788d8', 1),
('event', 'deadline', 'Deadline', 'Project or task deadline', '#dc3545', 2),
('event', 'milestone', 'Milestone', 'Project milestone', '#28a745', 3),
('event', 'training', 'Training', 'Training session', '#fd7e14', 4),
('event', 'review', 'Review', 'Code or project review', '#6f42c1', 5),
('event', 'other', 'Other', 'Other type of event', '#6c757d', 6);

-- Insert event attendance statuses
INSERT INTO `status_lookup` (`type`, `code`, `name`, `description`, `color`, `order_index`) VALUES
('event_status', 'invited', 'Invited', 'User is invited to attend', '#17a2b8', 1),
('event_status', 'accepted', 'Accepted', 'User accepted the invitation', '#28a745', 2),
('event_status', 'declined', 'Declined', 'User declined the invitation', '#dc3545', 3),
('event_status', 'tentative', 'Tentative', 'User response is tentative', '#ffc107', 4),
('event_status', 'no_response', 'No Response', 'User has not responded', '#6c757d', 5);

-- Insert sample events data
INSERT INTO `events` (`title`, `description`, `event_type`, `start_datetime`, `end_datetime`, `location`, `project_id`, `created_by`, `is_active`, `is_delete`) VALUES
('Project Kickoff Meeting', 'Initial project kickoff meeting to discuss objectives and timeline', 'meeting', NOW() + INTERVAL 1 DAY, NOW() + INTERVAL 1 DAY + INTERVAL 2 HOUR, 'Conference Room A', 1, 1, 1, 0),
('Sprint Planning', 'Sprint planning session for the next development cycle', 'meeting', NOW() + INTERVAL 3 DAY, NOW() + INTERVAL 3 DAY + INTERVAL 1 HOUR, 'Online - Zoom', 1, 1, 1, 0),
('Code Review Session', 'Weekly code review and quality assurance session', 'review', NOW() + INTERVAL 5 DAY, NOW() + INTERVAL 5 DAY + INTERVAL 90 MINUTE, 'Development Lab', 1, 1, 1, 0),
('Project Milestone Deadline', 'First milestone delivery deadline', 'deadline', NOW() + INTERVAL 14 DAY, NOW() + INTERVAL 14 DAY + INTERVAL 1 HOUR, NULL, 1, 1, 1, 0),
('Team Training - Agile Methodology', 'Training session on Agile development practices', 'training', NOW() + INTERVAL 7 DAY, NOW() + INTERVAL 7 DAY + INTERVAL 3 HOUR, 'Training Room B', NULL, 1, 1, 0);

-- Insert sample event attendees
INSERT INTO `event_attendees` (`event_id`, `user_id`, `status`, `response_date`, `is_active`, `is_delete`) VALUES
-- Kickoff meeting attendees
(1, 1, 'accepted', NOW(), 1, 0),
(1, 2, 'accepted', NOW(), 1, 0),
-- Sprint planning attendees
(2, 1, 'accepted', NOW(), 1, 0),
(2, 2, 'tentative', NULL, 1, 0),
-- Code review attendees
(3, 1, 'accepted', NOW(), 1, 0),
(3, 2, 'invited', NULL, 1, 0),
-- Training attendees
(5, 1, 'accepted', NOW(), 1, 0),
(5, 2, 'accepted', NOW(), 1, 0);
