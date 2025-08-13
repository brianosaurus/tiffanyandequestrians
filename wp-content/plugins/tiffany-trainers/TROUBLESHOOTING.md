# Troubleshooting Guide

## Execution Timeout Error

If you're seeing "Maximum execution time of 30 seconds exceeded", follow these steps:

### 1. Check Plugin Activation
- Go to WordPress Admin → Plugins
- Ensure "Tiffany Trainers" is activated
- If not activated, click "Activate"

### 2. Check WordPress Loading
The error usually occurs when:
- The page is accessed directly without going through WordPress
- The plugin is called before WordPress is fully loaded
- There's a conflict with another plugin

### 3. Test the Plugin
1. Go to WordPress Admin → Trainers → Add New
2. Create a test trainer with minimal content
3. Try viewing the trainers page again

### 4. Debug Mode
If the issue persists:
1. Enable WordPress debug mode in wp-config.php
2. Check the error logs for specific error messages
3. Use the debug.php file in this plugin folder

### 5. Common Solutions

#### Solution A: Access through WordPress
- Don't access trainers.html directly
- Go through your WordPress site navigation
- Use the WordPress permalink structure

#### Solution B: Check File Permissions
- Ensure wp-content/plugins/tiffany-trainers/ is readable
- Check that the main plugin file has proper permissions

#### Solution C: Plugin Conflict
- Temporarily deactivate other plugins
- Test if the issue persists
- Reactivate plugins one by one to identify conflicts

### 6. Fallback Content
The page now includes fallback content that will display if:
- WordPress isn't loaded
- The plugin isn't active
- There's an error with the shortcode

### 7. Still Having Issues?
1. Check your server's PHP configuration
2. Increase PHP memory limit if needed
3. Check for infinite loops in theme files
4. Verify database connectivity

## Quick Test
Visit `/wp-admin/plugins.php` to ensure WordPress is working properly.
