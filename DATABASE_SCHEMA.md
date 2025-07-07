# Project Management System - Database Schema Documentation

## Overview
This document describes the comprehensive MySQL database schema for the Project Management System. The schema is designed with normalization, lookup tables, soft deletes, and extensibility in mind.

## Database Connection
- **Database Name**: `project_management_system`
- **User**: `root` (no password for local development)
- **Host**: `localhost`
- **Port**: `3306`

## Schema Features
- ✅ **Normalized Design**: Separate tables for users, projects, tasks with proper relationships
- ✅ **Lookup Tables**: Centralized status, priority, department, position, and role management
- ✅ **Soft Deletes**: `is_delete` field in all tables for data preservation
- ✅ **Active/Inactive States**: `is_active` field for enabling/disabling records
- ✅ **Audit Trail**: `created_at` and `updated_at` timestamps in all tables
- ✅ **Relationship Tables**: Many-to-many relationships properly normalized
- ✅ **Foreign Key Constraints**: Data integrity enforced at database level

## Table Structure

### Core Entity Tables

#### `users`
Primary user account information
- `id` (Primary Key)
- `username` (Unique)
- `email` (Unique)
- `password_hash`
- `email_verified_at`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

#### `user_profile`
Extended user profile information
- `id` (Primary Key)
- `user_id` (Foreign Key to users)
- `first_name`, `last_name`
- `phone`, `address`, `bio`
- `avatar_path`
- `date_of_birth`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

#### `projects`
Project master data
- `id` (Primary Key)
- `name`, `description`
- `start_date`, `end_date`
- `budget`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

#### `tasks`
Task master data
- `id` (Primary Key)
- `title`, `description`
- `due_date`
- `estimated_hours`, `actual_hours`
- `completion_percentage`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

### Lookup Tables

#### `status_lookup`
Centralized status management for projects and tasks
- `id` (Primary Key)
- `name` (Status name)
- `description`
- `color_code` (For UI display)
- `is_active`, `is_delete`

**Sample Data:**
- Planning, Active, On Hold, Completed, Cancelled
- To Do, In Progress, In Review, Completed, Cancelled

#### `priority_lookup`
Priority levels for projects and tasks
- `id` (Primary Key)
- `name` (Priority name)
- `description`
- `level` (Numeric level for sorting)
- `color_code`
- `is_active`, `is_delete`

**Sample Data:**
- Low (1), Medium (2), High (3), Critical (4), Urgent (5)

#### `department_lookup`
Organization departments
- `id` (Primary Key)
- `name`, `description`
- `is_active`, `is_delete`

#### `position_lookup`
Job positions within departments
- `id` (Primary Key)
- `name`, `description`
- `department_id` (Foreign Key to department_lookup)
- `is_active`, `is_delete`

#### `user_role_lookup`
User roles and permissions
- `id` (Primary Key)
- `name`, `description`
- `permissions` (JSON field for role permissions)
- `is_active`, `is_delete`

### Relationship Tables

#### `user_role`
Links users to their roles
- `id` (Primary Key)
- `user_id` (Foreign Key to users)
- `role_id` (Foreign Key to user_role_lookup)
- `assigned_date`
- `is_active`, `is_delete`

#### `user_access`
User department and position assignments
- `id` (Primary Key)
- `user_id` (Foreign Key to users)
- `department_id` (Foreign Key to department_lookup)
- `position_id` (Foreign Key to position_lookup)
- `is_active`, `is_delete`

#### `user_rel`
User-to-user relationships (manager, team lead, etc.)
- `id` (Primary Key)
- `user_id` (Foreign Key to users)
- `related_user_id` (Foreign Key to users)
- `relationship_type` (manager, team_member, etc.)
- `is_active`, `is_delete`

#### `project_status`
Links projects to their current status
- `id` (Primary Key)
- `project_id` (Foreign Key to projects)
- `status_id` (Foreign Key to status_lookup)
- `status_date`
- `is_active`, `is_delete`

#### `project_priority`
Links projects to their priority level
- `id` (Primary Key)
- `project_id` (Foreign Key to projects)
- `priority_id` (Foreign Key to priority_lookup)
- `priority_date`
- `is_active`, `is_delete`

#### `project_members`
Project team assignments
- `id` (Primary Key)
- `project_id` (Foreign Key to projects)
- `user_id` (Foreign Key to users)
- `role` (Project Manager, Developer, Designer, etc.)
- `joined_date`
- `is_active`, `is_delete`

#### `project_client`
Client assignments to projects
- `id` (Primary Key)
- `project_id` (Foreign Key to projects)
- `client_name`, `client_email`
- `client_company`
- `is_active`, `is_delete`

#### `task_status`
Links tasks to their current status
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `status_id` (Foreign Key to status_lookup)
- `status_date`
- `is_active`, `is_delete`

#### `task_priority`
Links tasks to their priority level
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `priority_id` (Foreign Key to priority_lookup)
- `priority_date`
- `is_active`, `is_delete`

#### `task_assignment`
Task assignments to users
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `user_id` (Foreign Key to users)
- `assigned_date`
- `is_active`, `is_delete`

#### `task_ownership`
Task ownership and project relationships
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `project_id` (Foreign Key to projects)
- `owner_user_id` (Foreign Key to users)
- `is_active`, `is_delete`

### Supporting Tables

#### `task_comments`
Comments and notes on tasks
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `user_id` (Foreign Key to users)
- `comment`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

#### `task_attachments`
File attachments for tasks
- `id` (Primary Key)
- `task_id` (Foreign Key to tasks)
- `user_id` (Foreign Key to users)
- `filename`, `original_filename`
- `file_path`, `file_size`
- `mime_type`
- `is_active`, `is_delete`
- `created_at`, `updated_at`

#### `activity_logs`
System activity and audit trail
- `id` (Primary Key)
- `user_id` (Foreign Key to users)
- `action` (created, updated, deleted, etc.)
- `table_name`, `record_id`
- `old_values`, `new_values` (JSON fields)
- `ip_address`, `user_agent`
- `created_at`

## Sample Data

### Default User
- **Username**: `admin`
- **Email**: `admin@projectmanagement.com`
- **Password**: `admin123` (hashed)
- **Role**: Super Admin

### Sample Project
- **Name**: `Website Redesign Project`
- **Status**: `Active`
- **Priority**: `High`
- **Team Members**: Admin user assigned as Project Manager

### Sample Tasks
- 7 tasks created with various statuses and priorities
- Tasks assigned to different phases of the website redesign project

## Usage Examples

### Soft Delete Operations
```sql
-- Soft delete a user
UPDATE users SET is_delete = 1 WHERE id = 1;

-- Restore a soft deleted user
UPDATE users SET is_delete = 0 WHERE id = 1;

-- Get active, non-deleted users
SELECT * FROM users WHERE is_active = 1 AND is_delete = 0;
```

### Status and Priority Queries
```sql
-- Get all active projects with their status and priority
SELECT 
    p.name,
    sl.name as status,
    pl.name as priority
FROM projects p
LEFT JOIN project_status ps ON p.id = ps.project_id AND ps.is_active = 1
LEFT JOIN status_lookup sl ON ps.status_id = sl.id
LEFT JOIN project_priority pp ON p.id = pp.project_id AND pp.is_active = 1
LEFT JOIN priority_lookup pl ON pp.priority_id = pl.id
WHERE p.is_active = 1 AND p.is_delete = 0;
```

### Project Team Members
```sql
-- Get project team members
SELECT 
    p.name as project_name,
    u.username,
    up.first_name,
    up.last_name,
    pm.role
FROM projects p
JOIN project_members pm ON p.id = pm.project_id
JOIN users u ON pm.user_id = u.id
LEFT JOIN user_profile up ON u.id = up.user_id
WHERE p.is_active = 1 AND p.is_delete = 0
AND pm.is_active = 1 AND pm.is_delete = 0;
```

## Database Setup Instructions

1. **Import the Schema**:
   ```bash
   # Using MySQL command line
   mysql -u root -p < database_setup.sql
   
   # Or import via phpMyAdmin
   ```

2. **Verify Installation**:
   - Access: `http://localhost/project-management-system/test_database_connection.php`
   - This will test the connection and display sample data

3. **Configure CodeIgniter 4**:
   - Database configuration is already set in the `.env` file
   - No additional configuration needed

## Future Extensibility

The schema is designed for easy extension:

1. **New Lookup Tables**: Add new lookup tables following the same pattern
2. **Additional User Fields**: Extend `user_profile` table
3. **Custom Project Types**: Add `project_type_lookup` table
4. **Time Tracking**: Add `time_logs` table for detailed time tracking
5. **File Management**: Extend `task_attachments` for project-level attachments
6. **Notifications**: Add notification tables for system alerts

## Indexes and Performance

All tables include appropriate indexes:
- Primary keys
- Foreign key indexes
- Composite indexes on `is_active` and `is_delete`
- Unique indexes on email and username

## Security Considerations

- Passwords are hashed using PHP's `password_hash()`
- Foreign key constraints ensure data integrity
- Soft deletes preserve audit trail
- Activity logs track all changes

## Next Steps

1. ✅ Database schema created and populated
2. ✅ Sample data inserted
3. ✅ Connection verified
4. 🔄 **Next**: Create CodeIgniter 4 models for each table
5. 🔄 **Next**: Implement authentication system
6. 🔄 **Next**: Build admin dashboard for lookup table management
7. 🔄 **Next**: Create project and task management interfaces
