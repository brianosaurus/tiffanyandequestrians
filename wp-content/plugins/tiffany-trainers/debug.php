<?php
/**
 * Debug file for Tiffany Trainers plugin
 * This file helps identify what's causing execution timeouts
 */

// Check if this file is being accessed directly
if (!defined('ABSPATH')) {
    echo "This file cannot be accessed directly.";
    exit;
}

// Test basic WordPress functions
echo "<h2>WordPress Environment Check</h2>";
echo "<p>ABSPATH defined: " . (defined('ABSPATH') ? 'Yes' : 'No') . "</p>";
echo "<p>WP_Query available: " . (class_exists('WP_Query') ? 'Yes' : 'No') . "</p>";
echo "<p>get_posts function: " . (function_exists('get_posts') ? 'Yes' : 'No') . "</p>";
echo "<p>do_shortcode function: " . (function_exists('do_shortcode') ? 'Yes' : 'No') . "</p>";

// Test database connection
global $wpdb;
if (isset($wpdb)) {
    echo "<p>Database connection: " . ($wpdb->check_connection() ? 'OK' : 'Failed') . "</p>";
} else {
    echo "<p>Database connection: Not available</p>";
}

// Test custom post type
$trainers = get_posts(array(
    'post_type' => 'trainer',
    'post_status' => 'publish',
    'numberposts' => 5
));

echo "<p>Trainers found: " . count($trainers) . "</p>";

if (!empty($trainers)) {
    echo "<h3>Available Trainers:</h3>";
    foreach ($trainers as $trainer) {
        echo "<p>- " . $trainer->post_title . "</p>";
    }
} else {
    echo "<p>No trainers found in database.</p>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> If you see this debug output, the plugin is working but there might be an issue with the shortcode execution.</p>";
?>
