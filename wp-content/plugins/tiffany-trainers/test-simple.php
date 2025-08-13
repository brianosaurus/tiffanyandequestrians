<?php
/**
 * Simple test file for Tiffany Trainers plugin
 */

// Check if WordPress is loaded
if (!defined('ABSPATH')) {
    echo "<h2>WordPress Not Loaded</h2>";
    echo "<p>This file must be accessed through WordPress.</p>";
    exit;
}

echo "<h2>Simple Plugin Test</h2>";

// Test 1: Check if our function exists
if (function_exists('get_tiffany_trainers')) {
    echo "<p>✅ get_tiffany_trainers function exists</p>";
    
    // Test 2: Call the function
    $trainers = get_tiffany_trainers();
    echo "<p>✅ Function called successfully</p>";
    echo "<p>📊 Trainers found: " . count($trainers) . "</p>";
    
    // Test 3: Show trainer details
    if (!empty($trainers)) {
        echo "<h3>Trainer Details:</h3>";
        foreach ($trainers as $index => $trainer) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<h4>Trainer " . ($index + 1) . ":</h4>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($trainer['title']) . "</p>";
            echo "<p><strong>Title:</strong> " . htmlspecialchars($trainer['title_meta']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($trainer['description']) . "</p>";
            echo "<p><strong>Has Image:</strong> " . ($trainer['image'] ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Content Length:</strong> " . strlen($trainer['content']) . " characters</p>";
            echo "</div>";
        }
    } else {
        echo "<p>❌ No trainers found in database</p>";
        echo "<p>Make sure you have created trainers in WordPress Admin → Trainers</p>";
    }
    
} else {
    echo "<p>❌ get_tiffany_trainers function does not exist</p>";
    echo "<p>This means the plugin is not loaded properly.</p>";
}

// Test 4: Check if post type exists
$post_types = get_post_types([], 'names');
if (in_array('tiffany_trainer', $post_types)) {
    echo "<p>✅ 'tiffany_trainer' post type exists</p>";
} else {
    echo "<p>❌ 'tiffany_trainer' post type does not exist</p>";
    echo "<p>Available post types: " . implode(', ', $post_types) . "</p>";
}

// Test 5: Check for any posts of this type
$query = new WP_Query([
    'post_type' => 'tiffany_trainer',
    'post_status' => 'any',
    'posts_per_page' => -1
]);

echo "<p>📊 Posts found with WP_Query: " . $query->found_posts . "</p>";

echo "<hr>";
echo "<p><strong>Test completed.</strong></p>";
?>
