<?php
/**
 * Plugin Name: My ToDo CRUD API
 * Description: Secure ToDo List with Full CRUD, REST API, Nonce, and Custom DB Table.
 * Version: 1.0
 */

// Activation hook to create custom table
register_activation_hook(__FILE__, 'mytodo_create_table');
function mytodo_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'mytodo_tasks';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        task TEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('mytodo-script', plugin_dir_url(__FILE__) . 'mytodo.js', ['jquery'], null, true);
    wp_enqueue_style('mytodo-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

    wp_localize_script('mytodo-script', 'MyTodoData', [
        'nonce' => wp_create_nonce('wp_rest'),
        'api_url' => rest_url('mytodo/v1/tasks'),
    ]);
});

// Register REST API routes
add_action('rest_api_init', function() {
    register_rest_route('mytodo/v1', '/tasks', [
        'methods' => 'GET',
        'callback' => 'mytodo_get_tasks',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('mytodo/v1', '/tasks', [
        'methods' => 'POST',
        'callback' => 'mytodo_add_task',
        'permission_callback' => '__return_true',
        'args' => [ 'task' => ['required' => true] ],
    ]);

    register_rest_route('mytodo/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'mytodo_update_task',
        'permission_callback' => '__return_true',
        'args' => [ 'task' => ['required' => true] ],
    ]);

    register_rest_route('mytodo/v1', '/tasks/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'mytodo_delete_task',
        'permission_callback' => '__return_true',
    ]);
});

// Helper: Check Nonce for security
function mytodo_check_nonce() {
    $nonce = $_SERVER['HTTP_X_WP_NONCE'] ?? '';
    if (!wp_verify_nonce($nonce, 'wp_rest')) {
        return new WP_REST_Response(['message' => 'Invalid nonce'], 403);
    }
    return true;
}

// Get all tasks
function mytodo_get_tasks() {
    global $wpdb;
    $table = $wpdb->prefix . 'mytodo_tasks';
    $tasks = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
    return rest_ensure_response($tasks);
}

// Add a task
function mytodo_add_task($request) {
    if (($check = mytodo_check_nonce()) !== true) return $check;

    global $wpdb;
    $table = $wpdb->prefix . 'mytodo_tasks';
    $task = sanitize_text_field($request['task']);
    $wpdb->insert($table, ['task' => $task]);
    return mytodo_get_tasks();
}

// Update a task
function mytodo_update_task($request) {
    if (($check = mytodo_check_nonce()) !== true) return $check;

    global $wpdb;
    $table = $wpdb->prefix . 'mytodo_tasks';
    $id = (int)$request['id'];
    $task = sanitize_text_field($request['task']);
    $wpdb->update($table, ['task' => $task], ['id' => $id]);
    return mytodo_get_tasks();
}

// Delete a task
function mytodo_delete_task($request) {
    if (($check = mytodo_check_nonce()) !== true) return $check;

    global $wpdb;
    $table = $wpdb->prefix . 'mytodo_tasks';
    $id = (int)$request['id'];
    $wpdb->delete($table, ['id' => $id]);
    return mytodo_get_tasks();
}
