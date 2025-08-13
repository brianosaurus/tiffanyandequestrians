<?php
/**
 * Plugin Name: Tiffany & Equestrians Events Manager
 * Description: Custom events management for Tiffany & Equestrians website
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TiffanyEventsManager {
    
    public function __construct() {
        add_action('init', array($this, 'register_events_post_type'));
        add_action('add_meta_boxes', array($this, 'add_events_meta_boxes'));
        add_action('save_post', array($this, 'save_events_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_get_events_data', array($this, 'get_events_data'));
        add_action('pre_get_posts', array($this, 'set_default_admin_order'));
    }
    
    /**
     * Register custom post type for events
     */
    public function register_events_post_type() {
        $labels = array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'menu_name' => 'Events',
            'add_new' => 'Add New Event',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'new_item' => 'New Event',
            'view_item' => 'View Event',
            'search_items' => 'Search Events',
            'not_found' => 'No events found',
            'not_found_in_trash' => 'No events found in trash'
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'events'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
            'orderby' => 'meta_value_num',
            'meta_key' => '_event_display_order',
            'order' => 'ASC'
        );
        
        register_post_type('tiffany_event', $args);
    }
    
    /**
     * Add custom meta boxes for event details
     */
    public function add_events_meta_boxes() {
        add_meta_box(
            'event_details',
            'Event Details',
            array($this, 'render_event_details_meta_box'),
            'tiffany_event',
            'normal',
            'high'
        );
    }
    
    /**
     * Render the event details meta box
     */
    public function render_event_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('save_event_details', 'event_details_nonce');
        
        // Get existing values
        $city_state = get_post_meta($post->ID, '_event_city_state', true);
        $event_date = get_post_meta($post->ID, '_event_date', true);
        $flyer_url = get_post_meta($post->ID, '_event_flyer_url', true);
        $display_order = get_post_meta($post->ID, '_event_display_order', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="event_city_state">City & State</label>
                </th>
                <td>
                    <input type="text" id="event_city_state" name="event_city_state" 
                           value="<?php echo esc_attr($city_state); ?>" class="regular-text" 
                           placeholder="e.g., Monroe, WA" />
                    <p class="description">Enter the city and state where the event takes place.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="event_date">Event Date</label>
                </th>
                <td>
                    <input type="text" id="event_date" name="event_date" 
                           value="<?php echo esc_attr($event_date); ?>" class="regular-text" 
                           placeholder="e.g., August 9-10, 2025" />
                    <p class="description">Enter the event date as a string (e.g., "August 9-10, 2025").</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="event_flyer_url">Flyer URL (Optional)</label>
                </th>
                <td>
                    <input type="url" id="event_flyer_url" name="event_flyer_url" 
                           value="<?php echo esc_attr($flyer_url); ?>" class="regular-text" 
                           placeholder="https://example.com/flyer.pdf" />
                    <p class="description">Enter the URL to the event flyer (optional).</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="event_display_order">Display Order</label>
                </th>
                <td>
                    <input type="number" id="event_display_order" name="event_display_order" 
                           value="<?php echo esc_attr($display_order); ?>" class="small-text" 
                           min="1" placeholder="1" />
                    <p class="description">Order in which this event appears (lower numbers appear first).</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save event meta data
     */
    public function save_events_meta($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['event_details_nonce']) || 
            !wp_verify_nonce($_POST['event_details_nonce'], 'save_event_details')) {
            return;
        }
        
        // Check if user has permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save event details
        if (isset($_POST['event_city_state'])) {
            update_post_meta($post_id, '_event_city_state', sanitize_text_field($_POST['event_city_state']));
        }
        
        if (isset($_POST['event_date'])) {
            update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
        }
        
        if (isset($_POST['event_flyer_url'])) {
            update_post_meta($post_id, '_event_flyer_url', esc_url_raw($_POST['event_flyer_url']));
        }
        
        if (isset($_POST['event_display_order'])) {
            update_post_meta($post_id, '_event_display_order', intval($_POST['event_display_order']));
        }
    }
    
    /**
     * Set default admin order for events list
     */
    public function set_default_admin_order($query) {
        if (!is_admin()) {
            return;
        }
        
        global $pagenow, $post_type;
        
        if ($pagenow === 'edit.php' && $post_type === 'tiffany_event' && !isset($_GET['orderby'])) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_event_display_order');
            $query->set('order', 'ASC');
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'tiffany_event') {
            wp_enqueue_media();
            
            // Add custom CSS for admin columns
            wp_add_inline_style('wp-admin', '
                .column-display_order { width: 80px; text-align: center; }
                .column-event_date { width: 120px; }
                .column-city_state { width: 120px; }
                .column-flyer { width: 100px; }
            ');
        }
    }
    
    /**
     * AJAX handler to get events data for the frontend
     */
    public function get_events_data() {
        // Check nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'get_events_nonce')) {
            wp_die('Security check failed');
        }
        
        $args = array(
            'post_type' => 'tiffany_event',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => '_event_display_order',
            'order' => 'ASC'
        );
        
        $events = get_posts($args);
        $events_data = array();
        
        foreach ($events as $event) {
            $image_id = get_post_thumbnail_id($event->ID);
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
            
            $events_data[] = array(
                'id' => $event->ID,
                'title' => $event->post_title,
                'content' => $event->post_content,
                'image_name' => $image_url ? basename($image_url) : '',
                'image_url' => $image_url,
                'city_state' => get_post_meta($event->ID, '_event_city_state', true),
                'date' => get_post_meta($event->ID, '_event_date', true),
                'flyer' => get_post_meta($event->ID, '_event_flyer_url', true),
                'order' => get_post_meta($event->ID, '_event_display_order', true) ?: 999
            );
        }
        
        wp_send_json_success($events_data);
    }
}

// Initialize the plugin
new TiffanyEventsManager();

// Make the function globally available
function get_tiffany_events($limit = -1) {
    $query = new WP_Query([
        'post_type' => 'tiffany_event',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'meta_value_num',
        'meta_key' => '_event_display_order',
        'order' => 'ASC',
    ]);
    $events = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) { $query->the_post();
            $id = get_the_ID();
            $image_id = get_post_thumbnail_id($id);
            $events[] = [
                'id' => $id,
                'title' => get_the_title(),
                'content' => get_the_content(),
                'image' => $image_id ? wp_get_attachment_image_url($image_id, 'large') : '',
                'thumbnail' => $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '',
                'city_state' => get_post_meta($id, '_event_city_state', true),
                'date' => get_post_meta($id, '_event_date', true),
                'flyer' => get_post_meta($id, '_event_flyer_url', true),
                'order' => get_post_meta($id, '_event_display_order', true) ?: 999,
            ];
        }
        wp_reset_postdata();
    }
    return $events;
}

// Add custom columns to events list
add_filter('manage_tiffany_event_posts_columns', function($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['display_order'] = 'Order';
    $new_columns['event_date'] = 'Event Date';
    $new_columns['city_state'] = 'Location';
    $new_columns['flyer'] = 'Flyer';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
});

// Populate custom columns
add_action('manage_tiffany_event_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'display_order':
            $order = get_post_meta($post_id, '_event_display_order', true);
            if ($order) {
                echo '<strong style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">' . esc_html($order) . '</strong>';
            } else {
                echo '<span style="color: #999;">Not set</span>';
            }
            break;
        case 'event_date':
            echo get_post_meta($post_id, '_event_date', true);
            break;
        case 'city_state':
            echo get_post_meta($post_id, '_event_city_state', true);
            break;
        case 'flyer':
            $flyer_url = get_post_meta($post_id, '_event_flyer_url', true);
            if ($flyer_url) {
                echo '<a href="' . esc_url($flyer_url) . '" target="_blank">View Flyer</a>';
            } else {
                echo '—';
            }
            break;
    }
}, 10, 2);

// Make columns sortable
add_filter('manage_edit-tiffany_event_sortable_columns', function($columns) {
    $columns['display_order'] = 'display_order';
    $columns['event_date'] = 'event_date';
    $columns['city_state'] = 'city_state';
    return $columns;
}); 