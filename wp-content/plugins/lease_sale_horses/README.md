# Lease & Sale Horses

A WordPress plugin to manage lease and sale horses through the WordPress admin interface.

## Setup Instructions

### 1. Activate the Plugin

1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find "Lease & Sale Horses" and click **Activate**

### 2. Access Horses Management

After activation, you'll see a new "Horses" menu item in your WordPress admin sidebar.

### 3. Adding Horses

1. Go to **Horses > Add New**
2. Fill in the horse details:
   - **Title**: Horse name (e.g., "DIAMOND Z BEAR")
   - **Content**: Additional content (optional)
   - **Featured Image**: Upload a horse image using WordPress media library
   - **Horse Details**:
     - **Horse Type**: Choose "For Lease" or "For Sale"
     - **Horse Details**: Age, height, breed (e.g., "13-year-old, 16.3h Zangersheide Gelding")
     - **Description**: Main description of the horse
     - **Additional Qualities**: Extra qualities and characteristics
   - **Layout Settings**:
     - **Image Position**: Choose "Left" or "Right" for image placement
     - **Display Order**: Order in which horses appear (lower numbers first)

### 4. Lease-Specific Fields

When "For Lease" is selected:
- **Lease Details**: Terms, conditions, and specific details
- **Lease Price**: Price or price range (e.g., "Mid-five figures for the year")
- **Urgency Note**: Any urgency or deadline information
- **Current Location**: Location and availability for trials

### 5. Sale-Specific Fields

When "For Sale" is selected:
- **Sale Price**: Price of the horse (e.g., "$150,000")
- **Sale Details**: Sale terms, conditions, and details

### 6. Video Options

- **Video URL**: Optional link to horse video
- **Video Button Text**: Custom text for video button (defaults to "Video")

## Features

- ✅ **WordPress Admin Integration**: Manage horses through familiar WordPress interface
- ✅ **Custom Post Type**: Dedicated "Horses" section in admin
- ✅ **Featured Images**: Upload horse images through WordPress media library
- ✅ **Flexible Layouts**: Choose image position (left or right)
- ✅ **Type-Specific Fields**: Different fields for lease vs sale horses
- ✅ **Optional Video**: Add video links with custom button text
- ✅ **Display Ordering**: Control the order horses appear on the page
- ✅ **Responsive Design**: Maintains existing responsive layout
- ✅ **Empty State**: Shows "No Lease or Sale Horses at This Time" when no horses are available

## Frontend Display

The lease-sale-horses page automatically displays all published horses with:
- **Alternating layouts** based on image position setting
- **Type-specific styling** (lease vs sale)
- **Conditional video buttons** (only shown if video URL is provided)
- **Proper separators** between horses
- **Fallback message** when no horses are available

## File Structure

```
wp/wp-content/plugins/lease_sale_horses/
├── lease_sale_horses.php    # Main plugin file
└── README.md               # This file
```

## Customization

### Adding More Fields

To add additional horse fields, edit the `render_horse_details_meta_box()` function in `lease_sale_horses.php`.

### Changing Display Order

Modify the `orderby` and `order` parameters in the WP_Query in `lease-sale-horses.html`.

### Styling

The frontend styling is handled by Tailwind CSS classes in `lease-sale-horses.html`.

## Support

For issues or questions, check the WordPress admin interface or refer to this README. 