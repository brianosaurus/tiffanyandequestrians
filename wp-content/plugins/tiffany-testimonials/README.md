# Tiffany & Equestrians Testimonials Manager

A WordPress plugin to manage testimonials with star ratings.

## Setup Instructions

### 1. Activate the Plugin

1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find "Tiffany & Equestrians Testimonials Manager" and click **Activate**

### 2. Access Testimonials Manager

After activation, you'll see a new "Testimonials" menu item in your WordPress admin sidebar.

## Features

### ✅ Add Testimonials
- **Testimonial Text**: Enter the testimonial content
- **Author Name**: Add the name of the person giving the testimonial
- **Star Rating**: Choose from 1-5 stars using a dropdown
- **Title**: Optional title for organizing testimonials

### ✅ Manage Testimonials
- **List View**: See all testimonials in a table format
- **Quick Edit**: Edit testimonials directly from the list
- **Bulk Actions**: Delete multiple testimonials at once
- **Sort & Filter**: Sort by date, author, or rating

### ✅ Display Options
- **Star Display**: Shows filled and empty stars (★★★☆☆)
- **Excerpt View**: Preview testimonials in admin list
- **Author Attribution**: Properly credits testimonial authors

## How to Use

### Adding a New Testimonial
1. Go to **Testimonials > Add New**
2. Enter a title (for admin reference)
3. Fill in the testimonial details:
   - **Testimonial Text**: The actual testimonial
   - **Author Name**: Who gave the testimonial
   - **Star Rating**: Select 1-5 stars
4. Click **Publish** to save

### Managing Testimonials
1. Go to **Testimonials > All Testimonials**
2. View all testimonials in a list
3. Click a testimonial to edit
4. Use bulk actions for multiple testimonials

### Displaying Testimonials

Use the `get_tiffany_testimonials()` function in your theme files:

```php
$testimonials = get_tiffany_testimonials();
foreach ($testimonials as $testimonial) {
    echo $testimonial['text'];        // Testimonial text
    echo $testimonial['author'];      // Author name
    echo $testimonial['rating'];      // Star rating (1-5)
}
```

## Integration with Your Site

The testimonials are automatically used by:
- **Testimonials Section**: The `_testimonials.html` include file
- **Homepage**: Featured testimonials section
- **Any Other Pages**: You can display testimonials anywhere using the function

## File Structure

```
wp/wp-content/plugins/tiffany-testimonials/
├── tiffany-testimonials.php    # Main plugin file
└── README.md                   # This file
```

## Support

For issues or questions, check the WordPress admin interface or refer to this README.