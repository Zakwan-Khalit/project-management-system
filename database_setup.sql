-- Create database for project management system
CREATE DATABASE IF NOT EXISTS `project_management_system` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Use the database
USE `project_management_system`;

-- Lookup Tables for Dropdowns and References

-- Status lookup table
CREATE TABLE `status_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(50) NOT NULL, -- 'project', 'task', 'user'
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `color` varchar(7) DEFAULT NULL, -- hex color code
    `order_index` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `type_code` (`type`, `code`),
    KEY `type` (`type`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Priority lookup table
CREATE TABLE `priority_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(50) NOT NULL, -- 'project', 'task'
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `color` varchar(7) DEFAULT NULL,
    `level` int(11) DEFAULT 0, -- numerical priority level
    `order_index` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `type_code` (`type`, `code`),
    KEY `type` (`type`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Department lookup table
CREATE TABLE `department_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `manager_id` int(11) DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Position lookup table
CREATE TABLE `position_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `department_id` int(11) DEFAULT NULL,
    `level` int(11) DEFAULT 0, -- seniority level
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    KEY `department_id` (`department_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User role lookup table
CREATE TABLE `user_role_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(50) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text,
    `permissions` text, -- JSON permissions
    `level` int(11) DEFAULT 0, -- hierarchy level
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Core User Tables

-- Users table (simplified)
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `last_login_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User profile table
CREATE TABLE `user_profile` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `avatar` varchar(255) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `bio` text,
    `address` text,
    `timezone` varchar(50) DEFAULT 'UTC',
    `language` varchar(10) DEFAULT 'en',
    `date_format` varchar(20) DEFAULT 'Y-m-d',
    `time_format` varchar(20) DEFAULT 'H:i:s',
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User role relationship table
CREATE TABLE `user_role` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `role_id` int(11) NOT NULL,
    `assigned_by` int(11) DEFAULT NULL,
    `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_role` (`user_id`, `role_id`),
    KEY `user_id` (`user_id`),
    KEY `role_id` (`role_id`),
    KEY `assigned_by` (`assigned_by`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User access/permissions table
CREATE TABLE `user_access` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `resource` varchar(100) NOT NULL, -- table/module name
    `resource_id` int(11) DEFAULT NULL, -- specific record ID
    `permission` varchar(50) NOT NULL, -- create, read, update, delete
    `granted_by` int(11) DEFAULT NULL,
    `granted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `resource` (`resource`),
    KEY `granted_by` (`granted_by`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User relationship table (reporting structure, etc.)
CREATE TABLE `user_rel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `related_user_id` int(11) NOT NULL,
    `relationship_type` varchar(50) NOT NULL, -- 'manager', 'subordinate', 'colleague', 'mentor'
    `department_id` int(11) DEFAULT NULL,
    `position_id` int(11) DEFAULT NULL,
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `related_user_id` (`related_user_id`),
    KEY `relationship_type` (`relationship_type`),
    KEY `department_id` (`department_id`),
    KEY `position_id` (`position_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project Core Tables

-- Projects table (simplified)
CREATE TABLE `projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `code` varchar(50) DEFAULT NULL, -- project code/identifier
    `description` text,
    `budget` decimal(15,2) DEFAULT NULL,
    `start_date` date DEFAULT NULL,
    `end_date` date DEFAULT NULL,
    `actual_start_date` date DEFAULT NULL,
    `actual_end_date` date DEFAULT NULL,
    `progress` decimal(5,2) DEFAULT 0.00,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project status relationship
CREATE TABLE `project_status` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `status_id` int(11) NOT NULL,
    `changed_by` int(11) DEFAULT NULL,
    `notes` text,
    `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT NULL,
    `is_current` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `project_id` (`project_id`),
    KEY `status_id` (`status_id`),
    KEY `changed_by` (`changed_by`),
    KEY `is_current` (`is_current`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project priority relationship
CREATE TABLE `project_priority` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `priority_id` int(11) NOT NULL,
    `changed_by` int(11) DEFAULT NULL,
    `notes` text,
    `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT NULL,
    `is_current` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `project_id` (`project_id`),
    KEY `priority_id` (`priority_id`),
    KEY `changed_by` (`changed_by`),
    KEY `is_current` (`is_current`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project team members relationship
CREATE TABLE `project_members` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `role` varchar(50) DEFAULT 'member', -- 'manager', 'lead', 'member', 'client'
    `assigned_by` int(11) DEFAULT NULL,
    `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `left_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `project_user_active` (`project_id`, `user_id`, `is_active`),
    KEY `project_id` (`project_id`),
    KEY `user_id` (`user_id`),
    KEY `assigned_by` (`assigned_by`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project client information
CREATE TABLE `project_client` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `client_name` varchar(255) NOT NULL,
    `client_email` varchar(100) DEFAULT NULL,
    `client_phone` varchar(20) DEFAULT NULL,
    `client_address` text,
    `contact_person` varchar(255) DEFAULT NULL,
    `contract_value` decimal(15,2) DEFAULT NULL,
    `contract_start_date` date DEFAULT NULL,
    `contract_end_date` date DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `project_id` (`project_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task Core Tables

-- Tasks table (simplified)
CREATE TABLE `tasks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text,
    `start_date` date DEFAULT NULL,
    `due_date` date DEFAULT NULL,
    `completed_date` date DEFAULT NULL,
    `estimated_hours` decimal(8,2) DEFAULT NULL,
    `actual_hours` decimal(8,2) DEFAULT NULL,
    `progress` decimal(5,2) DEFAULT 0.00,
    `order_index` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `project_id` (`project_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task status relationship
CREATE TABLE `task_status` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `status_id` int(11) NOT NULL,
    `changed_by` int(11) DEFAULT NULL,
    `notes` text,
    `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT NULL,
    `is_current` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `status_id` (`status_id`),
    KEY `changed_by` (`changed_by`),
    KEY `is_current` (`is_current`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task priority relationship
CREATE TABLE `task_priority` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `priority_id` int(11) NOT NULL,
    `changed_by` int(11) DEFAULT NULL,
    `notes` text,
    `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT NULL,
    `is_current` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `priority_id` (`priority_id`),
    KEY `changed_by` (`changed_by`),
    KEY `is_current` (`is_current`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task assignment relationship
CREATE TABLE `task_assignment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `assigned_by` int(11) DEFAULT NULL,
    `role` varchar(50) DEFAULT 'assignee', -- 'assignee', 'reviewer', 'collaborator'
    `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `unassigned_at` timestamp NULL DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `user_id` (`user_id`),
    KEY `assigned_by` (`assigned_by`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task creation/ownership tracking
CREATE TABLE `task_ownership` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `created_by` int(11) NOT NULL,
    `owned_by` int(11) DEFAULT NULL, -- current owner (can be different from creator)
    `transferred_by` int(11) DEFAULT NULL,
    `transferred_at` timestamp NULL DEFAULT NULL,
    `is_current` tinyint(1) DEFAULT 1,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `created_by` (`created_by`),
    KEY `owned_by` (`owned_by`),
    KEY `is_current` (`is_current`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Supporting Tables

-- Activity log table
CREATE TABLE `activity_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `table_name` varchar(50) DEFAULT NULL,
    `record_id` int(11) DEFAULT NULL,
    `old_values` text,
    `new_values` text,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `table_name` (`table_name`),
    KEY `action` (`action`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task comments table
CREATE TABLE `task_comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `comment` text NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `user_id` (`user_id`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Task attachments table
CREATE TABLE `task_attachments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `filename` varchar(255) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `file_size` int(11) DEFAULT NULL,
    `file_type` varchar(100) DEFAULT NULL,
    `uploaded_by` int(11) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`),
    KEY `uploaded_by` (`uploaded_by`),
    KEY `is_active` (`is_active`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add foreign key constraints
ALTER TABLE `position_lookup` ADD CONSTRAINT `fk_position_department` FOREIGN KEY (`department_id`) REFERENCES `department_lookup` (`id`) ON DELETE SET NULL;

-- User table constraints
ALTER TABLE `user_profile` ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_role` ADD CONSTRAINT `fk_user_role_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_role` ADD CONSTRAINT `fk_user_role_role` FOREIGN KEY (`role_id`) REFERENCES `user_role_lookup` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_role` ADD CONSTRAINT `fk_user_role_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_access` ADD CONSTRAINT `fk_user_access_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_access` ADD CONSTRAINT `fk_user_access_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_related_user` FOREIGN KEY (`related_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_department` FOREIGN KEY (`department_id`) REFERENCES `department_lookup` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_position` FOREIGN KEY (`position_id`) REFERENCES `position_lookup` (`id`) ON DELETE SET NULL;

-- Project table constraints
ALTER TABLE `project_status` ADD CONSTRAINT `fk_project_status_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_status` ADD CONSTRAINT `fk_project_status_status` FOREIGN KEY (`status_id`) REFERENCES `status_lookup` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_status` ADD CONSTRAINT `fk_project_status_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `project_priority` ADD CONSTRAINT `fk_project_priority_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_priority` ADD CONSTRAINT `fk_project_priority_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority_lookup` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_priority` ADD CONSTRAINT `fk_project_priority_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `project_members` ADD CONSTRAINT `fk_members_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_members` ADD CONSTRAINT `fk_members_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `project_members` ADD CONSTRAINT `fk_members_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `project_client` ADD CONSTRAINT `fk_client_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

-- Task table constraints
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_status` ADD CONSTRAINT `fk_task_status_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_status` ADD CONSTRAINT `fk_task_status_status` FOREIGN KEY (`status_id`) REFERENCES `status_lookup` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_status` ADD CONSTRAINT `fk_task_status_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_priority` ADD CONSTRAINT `fk_task_priority_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_priority` ADD CONSTRAINT `fk_task_priority_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority_lookup` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_priority` ADD CONSTRAINT `fk_task_priority_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_assignment` ADD CONSTRAINT `fk_assignment_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_assignment` ADD CONSTRAINT `fk_assignment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_assignment` ADD CONSTRAINT `fk_assignment_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_ownership` ADD CONSTRAINT `fk_ownership_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_ownership` ADD CONSTRAINT `fk_ownership_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_ownership` ADD CONSTRAINT `fk_ownership_owned_by` FOREIGN KEY (`owned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Supporting table constraints
ALTER TABLE `activity_logs` ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_comments` ADD CONSTRAINT `fk_comments_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_comments` ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_attachments` ADD CONSTRAINT `fk_attachments_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_attachments` ADD CONSTRAINT `fk_attachments_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Insert lookup data

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

-- Insert sample data

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

-- Insert sample tasks
INSERT INTO `tasks` (`project_id`, `title`, `description`, `start_date`, `due_date`, `estimated_hours`) VALUES
(1, 'Setup Development Environment', 'Configure development environment with CodeIgniter 4, database, and frontend libraries', '2025-01-01', '2025-01-03', 8.00),
(1, 'Database Design', 'Design and implement database schema for the project management system', '2025-01-02', '2025-01-05', 16.00),
(1, 'User Authentication System', 'Implement login, registration, and user management features', '2025-01-04', '2025-01-10', 24.00),
(1, 'Project Management Module', 'Create project creation, editing, and management functionality', '2025-01-08', '2025-01-15', 32.00),
(1, 'Task Management System', 'Implement task creation, assignment, and tracking features', '2025-01-12', '2025-01-20', 40.00),
(1, 'Kanban Board', 'Create drag-and-drop kanban board for task management', '2025-01-18', '2025-01-25', 20.00),
(1, 'Dashboard and Reports', 'Build dashboard with charts and progress reports', '2025-01-22', '2025-02-01', 16.00);

-- Set task statuses
INSERT INTO `task_status` (`task_id`, `status_id`, `changed_by`, `notes`) VALUES
(1, 7, 1, 'Task completed successfully'), -- completed
(2, 7, 1, 'Database schema implemented'), -- completed
(3, 5, 1, 'Authentication system in progress'), -- in_progress
(4, 4, 1, 'Ready to start project module'), -- todo
(5, 4, 1, 'Waiting for project module completion'), -- todo
(6, 4, 1, 'Kanban board pending'), -- todo
(7, 4, 1, 'Dashboard development pending'); -- todo

-- Set task priorities
INSERT INTO `task_priority` (`task_id`, `priority_id`, `changed_by`, `notes`) VALUES
(1, 7, 1, 'High priority for project foundation'), -- high
(2, 7, 1, 'Critical for data structure'), -- high
(3, 7, 1, 'Essential for user access'), -- high
(4, 6, 1, 'Standard priority'), -- medium
(5, 6, 1, 'Standard priority'), -- medium
(6, 6, 1, 'Standard priority'), -- medium
(7, 5, 1, 'Lower priority for reporting'); -- low

-- Assign tasks to admin user
INSERT INTO `task_assignment` (`task_id`, `user_id`, `assigned_by`, `role`) VALUES
(1, 1, 1, 'assignee'),
(2, 1, 1, 'assignee'),
(3, 1, 1, 'assignee'),
(4, 1, 1, 'assignee'),
(5, 1, 1, 'assignee'),
(6, 1, 1, 'assignee'),
(7, 1, 1, 'assignee');

-- Set task ownership
INSERT INTO `task_ownership` (`task_id`, `created_by`, `owned_by`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1);
