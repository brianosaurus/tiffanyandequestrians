# Tiffany Trainers Plugin

A WordPress plugin to manage trainers for the Tiffany & Equestrians website, modeled after the working Meet the Horses and Lease/Sale Horses plugins.

## Features

- Custom post type for trainers
- Admin interface to add/edit trainers
- Custom fields for title and short description
- Featured image support for trainer photos
- Full biography support in the main content editor
- Simple function-based approach (no shortcodes)
- Maintains the same styling as the original trainers page

## Installation

1. Upload the `tiffany-trainers` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Trainers' in your admin menu to start adding trainers

## Adding a Trainer

1. Go to **Trainers > Add New** in your WordPress admin
2. Enter the trainer's name as the post title
3. Add a featured image (this will be the trainer's photo)
4. Fill in the "Title" field (e.g., "Owner & Head Trainer")
5. Add a short description in the "Short Description" field
6. Write the full biography in the main content editor
7. Publish the trainer

## How It Works

The plugin provides a simple function `get_tiffany_trainers()` that returns an array of trainer data. The trainers.html page calls this function directly to display the trainers.

## Styling

The plugin automatically applies the same Tailwind CSS classes and styling as the original trainers page, including:
- Responsive design
- Font families (Marcellus SC, Inter)
- Color scheme
- Layout and spacing
- Image styling with rounded corners and shadows

## Customization

The plugin uses the same CSS classes as your existing site, so any customizations you make to your theme's CSS will automatically apply to the trainer displays.

## Support

For support or customization requests, please contact your website developer.

## Technical Details

This plugin follows the same pattern as the working Meet the Horses and Lease/Sale Horses plugins:
- Simple class structure
- Direct function calls instead of shortcodes
- Minimal safety checks to prevent execution issues
- Clean, straightforward code
