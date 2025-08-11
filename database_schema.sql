-- Database Schema: Table Creation Only
-- Create database for keratsk
CREATE DATABASE IF NOT EXISTS `keratsk` 
CHARACTER SET utf8mb4 COLLATE=utf8mb4_general_ci;

-- Use the database
USE `keratsk`;

-- Lookup Tables for Dropdowns and References
-- Status lookup table
CREATE TABLE IF NOT EXISTS `status_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(32),
    `code` varchar(32),
    `name` varchar(128),
    `description` text,
    `color` varchar(16),
    `order_index` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Priority lookup table
CREATE TABLE IF NOT EXISTS `priority_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(32),
    `code` varchar(32),
    `name` varchar(128),
    `description` text,
    `color` varchar(16),
    `level` int(11),
    `order_index` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Department lookup table
CREATE TABLE IF NOT EXISTS `department_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(32),
    `name` varchar(128),
    `description` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Task Core Tables
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `template_id` int(11),
    `data` json,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `task_order` int(11) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Position lookup table
CREATE TABLE IF NOT EXISTS `position_lookup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(32),
    `name` varchar(128),
    `description` text,
    `level` int(11),
    `department_id` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for storing images related to tasks
CREATE TABLE IF NOT EXISTS `task_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `task_id` INT NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_address` VARCHAR(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_delete` tinyint(1) DEFAULT 0,
  `date_created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- User role lookup table (REMOVED - Using position_lookup + department_lookup instead)
-- Roles are now managed through position_lookup linked to department_lookup

-- Core User Tables
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(128),
    `password` varchar(255),
    `email_verified_at` datetime,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
    `date_modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_profile` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11),
    `full_name` varchar(128),
    `phone` varchar(32),
    `bio` text,
    `timezone` varchar(64),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- User role assignment table (REMOVED - Using user_rel instead)
-- User roles are now managed through user_rel linking to position_lookup and department_lookup

CREATE TABLE IF NOT EXISTS `user_access` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11),
    `granted_by` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_rel` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11),
    `related_user_id` int(11),
    `department_id` int(11),
    `position_id` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Project Core Tables
CREATE TABLE IF NOT EXISTS `projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128),
    `cost` decimal(12,2),
    `client` varchar(255),
    `budget` decimal(12,2),
    `start_date` date,
    `end_date` date,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
    `date_modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_status` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `status_id` int(11),
    `changed_by` int(11),
    `notes` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_priority` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `priority_id` int(11),
    `changed_by` int(11),
    `notes` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_members` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `user_id` int(11),
    `role` varchar(32),
    `fte` float,
    `end_date_involvement` date,
    `assigned_by` int(11),
    `joined_at` datetime,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_client` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `client_name` varchar(128),
    `client_email` varchar(128),
    `client_phone` varchar(32),
    `contact_person` varchar(128),
    `contract_value` decimal(12,2),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE IF NOT EXISTS `task_status` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `status_id` int(11),
    `changed_by` int(11),
    `notes` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `task_priority` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `priority_id` int(11),
    `changed_by` int(11),
    `notes` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `task_assignment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `user_id` int(11),
    `assigned_by` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `task_ownership` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `created_by` int(11),
    `owned_by` int(11),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Supporting Tables
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11),
    `project_id` int(11),
    `action` varchar(128),
    `description` text,
    `details` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    KEY `idx_project_activity` (`project_id`, `is_delete`, `date_created`),
    KEY `idx_user_activity` (`user_id`, `is_delete`, `date_created`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `task_comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `user_id` int(11),
    `comment` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `task_attachments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11),
    `uploaded_by` int(11),
    `file_path` varchar(255),
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `is_delete` (`is_delete`),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `header_lookup` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `column_name` VARCHAR(128) NOT NULL,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for dynamic task headers (no reference to header_lookup)
CREATE TABLE IF NOT EXISTS `task_headers` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `column_name` VARCHAR(128) NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `is_delete` TINYINT(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `project_scopes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `name` varchar(128) NOT NULL,
    `scope_order` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  `scope_lookup_id` INT DEFAULT NULL,
-- Table: scope_lookup
CREATE TABLE IF NOT EXISTS `scope_lookup` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: component_lookup
CREATE TABLE IF NOT EXISTS `component_lookup` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `scope_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  FOREIGN KEY (`scope_id`) REFERENCES `scope_lookup`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key to project_scopes for scope_lookup_id
ALTER TABLE `project_scopes` ADD CONSTRAINT `fk_project_scopes_scope_lookup` FOREIGN KEY (`scope_lookup_id`) REFERENCES `scope_lookup`(`id`);
-- Task Template Table
CREATE TABLE IF NOT EXISTS `task_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11),
    `scope_id` int(11),
    `name` varchar(128) NOT NULL,
    `description` text,
    `fields` text,
    `component_order` int(11) DEFAULT 0,
    `weightage` decimal(5,2) DEFAULT 0.00,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Main events table
CREATE TABLE IF NOT EXISTS `events` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text,
    `event_type` varchar(32) NOT NULL DEFAULT 'meeting',
    `start_datetime` datetime NOT NULL,
    `end_datetime` datetime NOT NULL,
    `location` varchar(255),
    `project_id` int(11),
    `created_by` int(11) NOT NULL,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `is_delete` (`is_delete`),
    KEY `idx_event_project` (`project_id`, `is_delete`),
    KEY `idx_event_datetime` (`start_datetime`, `end_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Event attendees table
CREATE TABLE IF NOT EXISTS `event_attendees` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `event_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `status` varchar(32) DEFAULT 'invited',
    `response_date` datetime,
    `notes` text,
    `is_active` tinyint(1) DEFAULT 1,
    `is_delete` tinyint(1) DEFAULT 0,
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `is_delete` (`is_delete`),
    KEY `idx_event_attendee` (`event_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Foreign Key Constraints
ALTER TABLE `position_lookup` ADD CONSTRAINT `fk_position_department` FOREIGN KEY (`department_id`) REFERENCES `department_lookup` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_profile` ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_access` ADD CONSTRAINT `fk_user_access_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_access` ADD CONSTRAINT `fk_user_access_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_related_user` FOREIGN KEY (`related_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_department` FOREIGN KEY (`department_id`) REFERENCES `department_lookup` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_rel` ADD CONSTRAINT `fk_user_rel_position` FOREIGN KEY (`position_id`) REFERENCES `position_lookup` (`id`) ON DELETE SET NULL;
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
ALTER TABLE `project_scopes` ADD CONSTRAINT `fk_scope_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
ALTER TABLE `tasks` ADD CONSTRAINT `fk_tasks_template` FOREIGN KEY (`template_id`) REFERENCES `task_templates` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_templates` ADD CONSTRAINT `fk_template_scope` FOREIGN KEY (`scope_id`) REFERENCES `project_scopes` (`id`) ON DELETE SET NULL;
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
ALTER TABLE `activity_logs` ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `activity_logs` ADD CONSTRAINT `fk_activity_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;
ALTER TABLE `task_comments` ADD CONSTRAINT `fk_comments_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_comments` ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_attachments` ADD CONSTRAINT `fk_attachments_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
ALTER TABLE `task_attachments` ADD CONSTRAINT `fk_attachments_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `events` ADD CONSTRAINT `fk_event_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;
ALTER TABLE `events` ADD CONSTRAINT `fk_event_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `event_attendees` ADD CONSTRAINT `fk_attendee_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
ALTER TABLE `event_attendees` ADD CONSTRAINT `fk_attendee_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
