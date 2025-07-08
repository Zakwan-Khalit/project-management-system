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

// Explicit routes for main controllers to ensure they work
$routes->get('projects', 'Projects::index');
$routes->get('projects/(:any)', 'Projects::$1');
$routes->post('projects/(:any)', 'Projects::$1');

$routes->get('tasks', 'Tasks::index');
$routes->get('tasks/(:any)', 'Tasks::$1');
$routes->post('tasks/(:any)', 'Tasks::$1');

// Specific kanban routes
$routes->get('tasks/kanban_select', 'Tasks::kanbanSelect');
$routes->get('tasks/kanbanselect', 'Tasks::kanbanSelect'); // Legacy support
$routes->get('tasks/kanban/(:num)', 'Tasks::kanban/$1');
$routes->post('tasks/kanban/(:num)', 'Tasks::kanban/$1');

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
