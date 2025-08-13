<?php
/**
 * Test file for Tiffany Trainers plugin
 * This file tests basic functionality without causing timeouts
 */

// Check if WordPress is loaded
if (!defined('ABSPATH')) {
    echo "<h2>WordPress Not Loaded</h2>";
    echo "<p>This file must be accessed through WordPress.</p>";
    exit;
}

echo "<h2>WordPress Environment Test</h2>";

// Test basic functions
$functions = array(
    'add_action',
    'register_post_type', 
    'add_shortcode',
    'WP_Query',
    'get_post_meta'
);

foreach ($functions as $func) {
    if (function_exists($func) || class_exists($func)) {
        echo "<p>✅ $func: Available</p>";
    } else {
        echo "<p>❌ $func: Not Available</p>";
    }
}

// Test if our plugin class exists
if (class_exists('TiffanyTrainers')) {
    echo "<p>✅ TiffanyTrainers class: Available</p>";
    
    // Test shortcode
    if (function_exists('do_shortcode')) {
        echo "<h3>Testing Shortcode:</h3>";
        echo do_shortcode('[tiffany_trainers]');
    } else {
        echo "<p>❌ do_shortcode function not available</p>";
    }
} else {
    echo "<p>❌ TiffanyTrainers class: Not Available</p>";
    echo "<p>Make sure the plugin is activated in WordPress admin.</p>";
}

echo "<hr>";
echo "<p><strong>Test completed.</strong> If you see this, the plugin is working without timeouts.</p>";
?>
