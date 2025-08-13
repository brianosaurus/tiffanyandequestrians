# Tiffany Gallery Plugin

A WordPress plugin for managing gallery images for the Tiffany & Equestrians website with ordering capabilities.

## Features

- **Custom Post Type**: Creates a `tiffany_gallery` post type for managing gallery images
- **Ordering System**: Set custom display order for each image (lower numbers appear first)
- **Image Management**: Upload and manage images through WordPress admin
- **Captions**: Add optional captions for each image
- **Responsive Display**: Images are displayed in a responsive grid layout
- **Hover Effects**: Beautiful hover effects with image titles and captions
- **Admin Overview**: See thumbnails, order, and gallery position at a glance
- **Smart Sorting**: Automatically sorts images by display order in admin

## Installation

1. Upload the `tiffany-gallery` folder to your `wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Gallery' in your WordPress admin menu

## Usage

### Adding Gallery Images

1. Go to **WordPress Admin → Gallery → Add New**
2. Give your image a title
3. Set the **Display Order** (lower numbers appear first)
4. Add an optional **Caption**
5. Set the **Featured Image** (this will be your gallery image)
6. Publish the image

### Managing Image Order

1. Go to **WordPress Admin → Gallery**
2. Edit any image and change the **Display Order** field
3. Images are automatically sorted by this number (ascending)

### Admin Interface Features

The Gallery admin page now shows:
- **Image Thumbnail**: Visual preview of each image
- **Title**: Image name
- **Order**: Display order number (highlighted)
- **Caption**: Image description (truncated if long)
- **Gallery Location**: Shows "Position X in gallery"
- **Date**: When the image was added

Images are automatically sorted by display order, making it easy to see the gallery sequence at a glance.

### Displaying Gallery Images

The plugin provides a global function `get_tiffany_gallery()` that returns an array of gallery images:

```php
$gallery = get_tiffany_gallery(); // Get all images
$preview = get_tiffany_gallery(3); // Get first 3 images
```

Each image object contains:
- `title`: Image title
- `caption`: Optional caption
- `image`: Full-size image URL
- `thumbnail`: Medium-size image URL
- `order`: Display order number

## Integration

The plugin is already integrated into:
- `gallery.html` - Main gallery page
- `trainers.html` - Gallery preview section
- `programs.html` - Gallery preview section
- `facility.html` - Gallery preview section

## Fallback System

If the plugin is not active or no images are found, the system falls back to static placeholder images to maintain the site's appearance.

## Styling

The gallery uses Tailwind CSS classes and custom CSS for:
- Responsive grid layout
- Hover effects and overlays
- Image scaling and transitions
- Consistent spacing and shadows

## Requirements

- WordPress 5.0+
- PHP 7.4+
- WordPress media library enabled
