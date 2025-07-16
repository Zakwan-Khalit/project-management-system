<?php

/**
 * General Helper Functions
 * 
 * This helper contains utility functions for debugging and general use
 */

if (!function_exists('mpr')) {
    /**
     * Modified print_r function for better debugging
     * 
     * @param mixed $d The data to print
     * @param bool $echo Whether to echo the output or return it
     * @return string|void
     */
    function mpr($d, $echo = TRUE)
    {
        if ($echo) {
            echo '<pre>' . print_r($d, true) . '</pre>';
        } else {
            return '<pre>' . print_r($d, true) . '</pre>';
        }
    }
}

if (!function_exists('pr')) {
    /**
     * Debug print function with file and line information
     * 
     * @param mixed $dump The data to dump
     * @param bool $exit Whether to exit after printing
     * @return void
     */
    function pr($dump = array(), $exit = true)
    {
        $debug = debug_backtrace();

        echo "<pre>";
        print_r($dump);
        echo "</pre>";

        echo 'DEBUG <b>File : </b>' . @$debug[0]['file'] . ' <b>Line : </b>' . @$debug[0]['line'];
        if ($exit) {
            exit;
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die function (Laravel style)
     * 
     * @param mixed ...$vars Variables to dump
     * @return void
     */
    function dd(...$vars)
    {
        $debug = debug_backtrace();
        
        echo '<style>
            .dd-container { 
                background: #f8f9fa; 
                border: 1px solid #dee2e6; 
                border-radius: 8px; 
                margin: 10px 0; 
                font-family: Monaco, Consolas, monospace; 
                font-size: 12px; 
            }
            .dd-header { 
                background: #6c757d; 
                color: white; 
                padding: 8px 12px; 
                font-weight: bold; 
            }
            .dd-content { 
                padding: 12px; 
                white-space: pre-wrap; 
            }
        </style>';
        
        foreach ($vars as $var) {
            echo '<div class="dd-container">';
            echo '<div class="dd-header">DEBUG: ' . @$debug[0]['file'] . ' (Line: ' . @$debug[0]['line'] . ')</div>';
            echo '<div class="dd-content">' . print_r($var, true) . '</div>';
            echo '</div>';
        }
        
        exit;
    }
}

if (!function_exists('vd')) {
    /**
     * Var dump function with better formatting
     * 
     * @param mixed $data The data to dump
     * @param bool $exit Whether to exit after dumping
     * @return void
     */
    function vd($data, $exit = true)
    {
        $debug = debug_backtrace();
        
        echo '<div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; margin: 10px 0; font-family: Monaco, Consolas, monospace; font-size: 12px;">';
        echo '<div style="background: #007bff; color: white; padding: 8px 12px; font-weight: bold;">VAR_DUMP: ' . @$debug[0]['file'] . ' (Line: ' . @$debug[0]['line'] . ')</div>';
        echo '<div style="padding: 12px;">';
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        echo '</div>';
        echo '</div>';
        
        if ($exit) {
            exit;
        }
    }
}

if (!function_exists('console_log')) {
    /**
     * Log data to browser console (useful for AJAX debugging)
     * 
     * @param mixed $data The data to log
     * @param string $type The console method to use (log, error, warn, info)
     * @return void
     */
    function console_log($data, $type = 'log')
    {
        $json_data = json_encode($data);
        echo "<script>console.{$type}({$json_data});</script>";
    }
}

if (!function_exists('log_debug')) {
    /**
     * Write debug information to CodeIgniter log
     * 
     * @param mixed $data The data to log
     * @param string $message Optional message
     * @return void
     */
    function log_debug($data, $message = 'DEBUG')
    {
        $debug = debug_backtrace();
        $file = basename(@$debug[0]['file']);
        $line = @$debug[0]['line'];
        
        log_message('debug', $message . " [{$file}:{$line}] " . print_r($data, true));
    }
}

/**
 * Get current user data from session
 * @param string|null $key Specific key to get from userdata, or null for all data
 * @return mixed
 */
if (!function_exists('user_data')) {
    function user_data($key = null) {
        try {
            $userData = session('userdata');
            
            if ($key === null) {
                return $userData;
            }
            
            return $userData[$key] ?? null;
        } catch (Exception $e) {
            // Return null if session access fails
            return null;
        }
    }
}

/**
 * Get current user ID
 * @return int|null
 */
if (!function_exists('user_id')) {
    function user_id() {
        return user_data('id');
    }
}

/**
 * Get current user email
 * @return string|null
 */
if (!function_exists('user_email')) {
    function user_email() {
        return user_data('email');
    }
}

/**
 * Get current user name
 * @param bool $full Whether to return full name or just first name
 * @return string
 */
if (!function_exists('user_name')) {
    function user_name($full = false) {
        if ($full) {
            $firstName = user_data('first_name') ?? '';
            $lastName = user_data('last_name') ?? '';
            return trim($firstName . ' ' . $lastName);
        }
        return user_data('first_name') ?? 'User';
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        try {
            return session('is_logged_in') === true && user_data() !== null;
        } catch (Exception $e) {
            return false;
        }
    }
}

/**
 * Check if user has specific role
 * @param string $role
 * @return bool
 */
if (!function_exists('has_role')) {
    function has_role($role) {
        return user_data('role') === $role;
    }
}

/**
 * Check if user is admin
 * @return bool
 */
if (!function_exists('is_admin')) {
    function is_admin() {
        return has_role('admin');
    }
}

if (!function_exists('getTaskStatus')) {
    function getTaskStatus($task) {
        // Try to return the status code, fallback to status_name or 'pending'
        return $task['status_code'] ?? 
               (isset($task['status_name']) ? strtolower(str_replace(' ', '_', $task['status_name'])) : 'pending');
    }
}

if (!function_exists('getTaskPriority')) {
    function getTaskPriority($task) {
        // Try to return the priority code, fallback to priority_name or 'medium'
        return $task['priority_code'] ??
               (isset($task['priority_name']) ? strtolower(str_replace(' ', '_', $task['priority_name'])) : 'medium');
    }
}
