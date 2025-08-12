# Tiffany & Equestrians Events Manager

A WordPress plugin to manage events through the WordPress admin interface.

## Setup Instructions

### 1. Activate the Plugin

1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find "Tiffany & Equestrians Events Manager" and click **Activate**

### 2. Access Events Management

After activation, you'll see a new "Events" menu item in your WordPress admin sidebar.

### 3. Adding Events

1. Go to **Events > Add New**
2. Fill in the event details:
   - **Title**: Event name (e.g., "Spring National Inaugural")
   - **Content**: Event description (optional)
   - **Featured Image**: Upload an image for the event
   - **Event Details**:
     - **City & State**: Location (e.g., "Monroe, WA")
     - **Event Date**: Date as string (e.g., "August 9-10, 2025")
     - **Flyer URL**: Optional link to event flyer

3. Click **Publish** to save the event

### 4. Managing Events

- **View All Events**: Go to **Events > All Events**
- **Edit Event**: Click on any event title to edit
- **Delete Event**: Use the trash link or bulk actions
- **Sort Events**: Click column headers to sort by date or location

### 5. Frontend Display

The events page (`events.html`) automatically displays all published events in a 4-column grid.

## Features

- ✅ **WordPress Admin Integration**: Manage events through familiar WordPress interface
- ✅ **Custom Post Type**: Dedicated "Events" section in admin
- ✅ **Featured Images**: Upload event images through WordPress media library
- ✅ **Custom Fields**: City/State, date, and flyer URL fields
- ✅ **Sortable Columns**: Sort events by date or location in admin
- ✅ **Responsive Display**: Events display in 4-column grid on frontend
- ✅ **Flyer Support**: Optional flyer links with styled buttons
- ✅ **Date Ordering**: Events automatically sorted by date

## File Structure

```
wp/wp-content/plugins/tiffany-events/
├── tiffany-events.php    # Main plugin file
└── README.md            # This file
```

## Customization

### Adding More Fields

To add additional event fields, edit the `render_event_details_meta_box()` function in `tiffany-events.php`.

### Changing Display Order

Modify the `orderby` and `order` parameters in the WP_Query in `events.html`.

### Styling

The frontend styling is handled by Tailwind CSS classes in `events.html`.

## Support

For issues or questions, check the WordPress admin interface or refer to this README. 