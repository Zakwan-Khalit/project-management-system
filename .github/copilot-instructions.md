<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

# Project Management System - Copilot Instructions

## Project Overview
This is a comprehensive project management system built with CodeIgniter 4 and modern frontend technologies. The system provides features for project tracking, task management, team collaboration, and progress monitoring.

## Technology Stack
- **Backend Framework**: CodeIgniter 4 (PHP)
- **Database**: MySQL with phpMyAdmin
- **Frontend Framework**: Bootstrap 5
- **JavaScript Libraries**: 
  - Chart.js for data visualization
  - ApexCharts for advanced charts
  - SortableJS for drag-and-drop functionality
  - SweetAlert2 for beautiful alerts and modals
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Poppins, Roboto)
- **CSS**: Bootstrap 5 + Custom CSS with CSS Variables

## Architecture Guidelines

### CodeIgniter 4 Patterns
- Follow MVC (Model-View-Controller) architecture
- Use CodeIgniter 4 naming conventions
- Implement proper routing in `app/Config/Routes.php`
- Use migrations for database schema changes
- Implement proper validation using CodeIgniter's validation library
- Use CodeIgniter's ORM/Query Builder for database operations

### Frontend Development
- Use Bootstrap 5 classes for responsive design
- Implement modern CSS with CSS custom properties (variables)
- Use Font Awesome icons consistently
- Ensure mobile-first responsive design
- Use Google Fonts (Poppins for headings, Roboto for body text)

### JavaScript Best Practices
- Use ES6+ syntax
- Implement proper event handling
- Use Chart.js for standard charts and ApexCharts for advanced visualizations
- Implement SortableJS for drag-and-drop features
- Use SweetAlert2 for all user notifications and confirmations
- Follow async/await patterns for API calls

### Database Design
- Use proper foreign key relationships
- Implement soft deletes where appropriate
- Use ENUM fields for status and priority fields
- Include created_at and updated_at timestamps
- Follow proper indexing strategies

### Security Considerations
- Use CodeIgniter's CSRF protection
- Implement proper input validation
- Use prepared statements (Query Builder handles this)
- Implement proper authentication and authorization
- Sanitize all user inputs

### Code Organization
- Group related functionality in appropriate controllers
- Use helper functions for common operations
- Implement proper error handling
- Use CodeIgniter's caching where appropriate
- Follow PSR coding standards

### UI/UX Guidelines
- Maintain consistent color scheme using CSS variables
- Use smooth transitions and hover effects
- Implement loading states for better user experience
- Use proper spacing and typography
- Ensure accessibility compliance
- Implement dark/light theme support where possible

### Performance Optimization
- Optimize database queries
- Use CodeIgniter's caching mechanisms
- Minimize JavaScript and CSS files
- Implement lazy loading for images
- Use CDN for external libraries when possible

## File Structure Preferences
- Views should be organized in logical folders
- Use proper namespacing for models and controllers
- Keep JavaScript in separate files or organized sections
- Use partials for reusable view components
- Maintain clean separation of concerns

## Development Workflow
- Use migrations for all database changes
- Implement proper error logging
- Use CodeIgniter's debugging tools in development
- Follow semantic versioning for releases
- Implement proper backup strategies for production

## Integration Notes
- All frontend libraries are locally hosted in `public/assets/`
- Database configuration is in `.env` file
- Use CodeIgniter's environment-specific configurations
- Implement proper API endpoints for AJAX operations
