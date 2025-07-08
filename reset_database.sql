-- Reset Database Script for Project Management System
-- This script will drop all existing tables and recreate the database from scratch

-- Disable foreign key checks to avoid constraint errors during drop
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all existing tables if they exist
DROP TABLE IF EXISTS `task_attachments`;
DROP TABLE IF EXISTS `task_comments`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `task_ownership`;
DROP TABLE IF EXISTS `task_assignment`;
DROP TABLE IF EXISTS `task_priority`;
DROP TABLE IF EXISTS `task_status`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `project_client`;
DROP TABLE IF EXISTS `project_members`;
DROP TABLE IF EXISTS `project_priority`;
DROP TABLE IF EXISTS `project_status`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `user_rel`;
DROP TABLE IF EXISTS `user_access`;
DROP TABLE IF EXISTS `user_role`;
DROP TABLE IF EXISTS `user_profile`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_role_lookup`;
DROP TABLE IF EXISTS `position_lookup`;
DROP TABLE IF EXISTS `department_lookup`;
DROP TABLE IF EXISTS `priority_lookup`;
DROP TABLE IF EXISTS `status_lookup`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Drop and recreate the database
DROP DATABASE IF EXISTS `project_management_system`;
CREATE DATABASE `project_management_system` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Use the new database
USE `project_management_system`;
