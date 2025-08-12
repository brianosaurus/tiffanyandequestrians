# Tiffany & Equestrians Gallery Manager

A WordPress plugin to manage gallery images in the root gallery directory.

## Setup Instructions

### 1. Activate the Plugin

1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find "Tiffany & Equestrians Gallery Manager" and click **Activate**

### 2. Access Gallery Manager

After activation, you'll see a new "Gallery Manager" menu item in your WordPress admin sidebar.

## Features

### ✅ Upload Images
- **Drag & Drop**: Simply drag images onto the upload area
- **Click to Select**: Click the upload area to browse and select files
- **Multiple Files**: Upload multiple images at once
- **Progress Bar**: See upload progress in real-time
- **File Validation**: Only allows JPG, PNG, GIF, and WebP images
- **Unique Filenames**: Automatically handles duplicate filenames

### ✅ View Gallery
- **Grid Layout**: Images displayed in a responsive grid
- **Thumbnails**: Each image shows a preview thumbnail
- **File Information**: Shows filename and file size
- **Alphabetical Sorting**: Images sorted by filename

### ✅ Manage Images
- **Delete Images**: Remove images with confirmation dialog
- **Copy URL**: Copy image URL to clipboard for use elsewhere
- **Real-time Updates**: Gallery refreshes automatically after changes

### ✅ Security Features
- **Permission Checks**: Only administrators can access
- **File Type Validation**: Prevents upload of non-image files
- **Path Validation**: Ensures files are only in the gallery directory
- **Nonce Protection**: All AJAX requests are secured

## How to Use

### Uploading Images
1. Go to **Gallery Manager** in your WordPress admin
2. Drag images onto the upload area or click to browse
3. Wait for upload to complete
4. Images will appear in the gallery grid below

### Managing Images
1. **View Images**: All images in the gallery directory are displayed
2. **Delete Image**: Click the "Delete" button and confirm
3. **Copy URL**: Click "Copy URL" to get the image URL for use in your site

### Gallery Directory
- **Location**: `/gallery/` in your website root directory
- **Auto-creation**: Directory is created automatically if it doesn't exist
- **File Types**: JPG, JPEG, PNG, GIF, WebP

## Integration with Your Site

The gallery images are stored in the `/gallery/` directory and can be accessed via:
- **URL Format**: `https://yoursite.com/gallery/filename.jpg`
- **File Path**: `/path/to/your/site/gallery/filename.jpg`

These images are automatically used by:
- **Gallery Page**: The dynamic gallery page displays all images from this directory
- **Programs Page**: Shows the first 3 images from this directory
- **Any Other Pages**: You can reference these images anywhere on your site

## File Structure

```
wp/wp-content/plugins/tiffany-gallery-manager/
├── tiffany-gallery-manager.php    # Main plugin file
└── README.md                      # This file
```

## Support

For issues or questions, check the WordPress admin interface or refer to this README. 