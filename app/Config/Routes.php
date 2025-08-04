
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Enable LEGACY auto routing for simple controller/method access
$routes->setAutoRoute(true);

// Default route
$routes->get('/', 'Home::index');

// Explicit auth routes to ensure proper routing
$routes->match(['GET', 'POST'], 'login', 'AuthController::login');
$routes->match(['GET', 'POST'], 'register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
// Note: profile route moved to Profile controller section below
$routes->get('settings', 'AuthController::settings');
$routes->get('dashboard', 'Home::dashboard');
$routes->post('dashboard/refresh', 'Home::refresh');

// Explicit routes for main controllers to ensure they work
$routes->get('projects', 'Projects::index');
$routes->get('projects/create', 'Projects::create'); // Route for creating projects
$routes->post('projects/create', 'Projects::create'); // Route for POST to create
$routes->get('projects/(:num)/edit', 'Projects::edit/$1'); // Route for editing projects
$routes->post('projects/edit/(:num)', 'Projects::edit/$1'); // Route for POST to edit
$routes->get('projects/view/(:num)', 'Projects::view/$1'); // Route for viewing projects
$routes->get('projects/members/(:num)', 'Projects::members/$1'); // Route for project members
$routes->get('projects/departments', 'Projects::departments'); // Route for departments
$routes->get('projects/departmentUsers/(:num)', 'Projects::departmentUsers/$1'); // Route for department users
$routes->post('projects/remove_project_member', 'Projects::remove_project_member'); // Route for removing members
$routes->get('projects/(:any)', 'Projects::$1');
$routes->post('projects/(:any)', 'Projects::$1');
$routes->get('projects/get_tasks_by_template/(:any)/(:num)', 'Projects::get_tasks_by_template/$1/$2');

$routes->post('projects/add_project_members', 'Projects::add_project_members');
$routes->post('projects/updateHeaders', 'Projects::updateHeaders');


$routes->get('tasks', 'Tasks::index');
$routes->get('tasks/myTasks', 'Tasks::myTasks');

// Specific kanban routes (must come before the generic route)
$routes->get('tasks/kanban_select', 'Tasks::kanbanSelect');
$routes->get('tasks/kanbanselect', 'Tasks::kanbanSelect'); // Legacy support
$routes->get('tasks/kanban/(:num)', 'Tasks::kanban/$1');
$routes->post('tasks/kanban/(:num)', 'Tasks::kanban/$1');

// Generic task routes (must come after specific routes)
$routes->get('tasks/(:any)', 'Tasks::$1');
$routes->post('tasks/(:any)', 'Tasks::$1');
$routes->post('task-images/upload', 'TaskImages::upload');
$routes->get('task-images/list/(:num)', 'TaskImages::list/$1');
$routes->post('task-images/delete/(:num)', 'TaskImages::delete/$1');
$routes->get('task-images/view/(:any)', 'TaskImages::view/$1');
$routes->get('task-images/test', 'TaskImages::test');

// Profile routes
$routes->get('profile', 'Profile::index');
$routes->get('profile/edit', 'Profile::edit');
$routes->post('profile/update', 'Profile::update');
$routes->get('profile/change-password', 'Profile::changePassword');
$routes->post('profile/update-password', 'Profile::updatePassword');

// Reports routes
$routes->get('reports', 'Reports::index');
$routes->get('reports/projects', 'Reports::projects');
$routes->get('reports/tasks', 'Reports::tasks');
$routes->get('reports/export/(:alpha)', 'Reports::export/$1');

// Events routes
// Events routes
$routes->get('events', 'Events::index');
$routes->get('events/create', 'Events::create');
$routes->post('events/create', 'Events::store');
$routes->get('events/edit/(:num)', 'Events::edit/$1');
$routes->post('events/edit/(:num)', 'Events::update/$1');
$routes->get('events/delete/(:num)', 'Events::delete/$1');
$routes->get('events/getCalendarEvents', 'Events::getCalendarEvents');
$routes->get('events/getProjectUsers/(:num)', 'Events::getProjectUsers/$1');
$routes->get('events/getAllUsers', 'Events::getAllUsers');
$routes->get('events/getDashboardEvents', 'Events::getDashboardEvents');

// Users routes
$routes->get('users', 'Users::index');
$routes->get('users/create', 'Users::create');
$routes->post('users/create', 'Users::store');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/edit/(:num)', 'Users::update/$1');
$routes->post('users/update/(:num)', 'Users::update/$1'); // AJAX update route
$routes->get('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/toggleStatus/(:num)', 'Users::toggleStatus/$1');
$routes->post('users/getPositionsByDepartment', 'Users::getPositionsByDepartment');

// Activity routes (previously project_list, project_task, task_dynamic)
$routes->get('activity', 'Activity::index');
$routes->get('activity/activity_scope/(:num)', 'Activity::activity_scope/$1');
$routes->get('activity/activity_dynamic/(:any)', 'Activity::activity_dynamic/$1');
$routes->get('activity/project_users/(:num)', 'Activity::project_users/$1');
$routes->post('activity/save_task', 'Activity::save_task');
$routes->post('activity/delete_task', 'Activity::delete_task');
$routes->post('activity/update_task_order', 'Activity::update_task_order');
$routes->post('activity/updateHeaders', 'Activity::updateHeaders');
$routes->post('activity/add_task_header', 'Activity::add_task_header');
$routes->get('activity/get_project_scopes', 'Activity::get_project_scopes');
$routes->get('activity/get_tasks_by_template/(:any)/(:num)', 'Activity::get_tasks_by_template/$1/$2');
$routes->post('activity/update_template_order', 'Activity::update_template_order');
$routes->post('activity/create_scope', 'Activity::create_scope');
$routes->post('activity/update_scope', 'Activity::update_scope');
$routes->post('activity/delete_scope', 'Activity::delete_scope');
$routes->post('activity/update_component_name', 'Activity::update_component_name');
$routes->post('activity/soft_delete_component', 'Activity::soft_delete_component');
$routes->post('activity/add_custom_template_to_scope', 'Activity::add_custom_template_to_scope');
$routes->post('activity/create_component', 'Activity::create_component');
$routes->post('activity/update_component_weightage', 'Activity::update_component_weightage');
$routes->get('activity/get_project_templates', 'Activity::get_project_templates');
$routes->get('activity/task_page/(:any)', 'Activity::activity_dynamic/$1');
