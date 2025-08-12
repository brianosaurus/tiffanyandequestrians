<?php
/**
 * Plugin Name: lease_sale_horses
 * Description: Manage lease and sale horses (entries, details, layout)
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LeaseSaleHorsesPlugin {
    
    public function __construct() {
        add_action('init', array($this, 'register_horses_post_type'));
        add_action('add_meta_boxes', array($this, 'add_horses_meta_boxes'));
        add_action('save_post', array($this, 'save_horses_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Register custom post type for horses
     */
    public function register_horses_post_type() {
        $labels = array(
            'name' => 'Lease/Sale Horses',
            'singular_name' => 'Horse',
            'menu_name' => 'Lease/Sale Horses',
            'add_new' => 'Add New Horse',
            'add_new_item' => 'Add New Horse',
            'edit_item' => 'Edit Horse',
            'new_item' => 'New Horse',
            'view_item' => 'View Horse',
            'search_items' => 'Search Horses',
            'not_found' => 'No horses found',
            'not_found_in_trash' => 'No horses found in trash'
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'horses'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-pets',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true
        );
        
        register_post_type('tiffany_horse', $args);
    }
    
    /**
     * Add custom meta boxes for horse details
     */
    public function add_horses_meta_boxes() {
        add_meta_box(
            'horse_details',
            'Horse Details',
            array($this, 'render_horse_details_meta_box'),
            'tiffany_horse',
            'normal',
            'high'
        );
        
        add_meta_box(
            'horse_layout',
            'Layout Settings',
            array($this, 'render_layout_meta_box'),
            'tiffany_horse',
            'side',
            'default'
        );
    }
    
    /**
     * Render the horse details meta box
     */
    public function render_horse_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('save_horse_details', 'horse_details_nonce');
        
        // Get existing values
        $horse_type = get_post_meta($post->ID, '_horse_type', true);
        $horse_details = get_post_meta($post->ID, '_horse_details', true);
        $horse_description = get_post_meta($post->ID, '_horse_description', true);
        $horse_qualities = get_post_meta($post->ID, '_horse_qualities', true);
        
        // Lease-specific fields
        $lease_details = get_post_meta($post->ID, '_lease_details', true);
        $lease_price = get_post_meta($post->ID, '_lease_price', true);
        $lease_urgency = get_post_meta($post->ID, '_lease_urgency', true);
        $lease_location = get_post_meta($post->ID, '_lease_location', true);
        
        // Sale-specific fields
        $sale_price = get_post_meta($post->ID, '_sale_price', true);
        $sale_details = get_post_meta($post->ID, '_sale_details', true);
        
        // Video fields
        $video_url = get_post_meta($post->ID, '_video_url', true);
        $video_text = get_post_meta($post->ID, '_video_text', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="horse_type">Horse Type</label>
                </th>
                <td>
                    <select id="horse_type" name="horse_type" class="regular-text">
                        <option value="">Select Type</option>
                        <option value="lease" <?php selected($horse_type, 'lease'); ?>>For Lease</option>
                        <option value="sale" <?php selected($horse_type, 'sale'); ?>>For Sale</option>
                    </select>
                    <p class="description">Choose whether this horse is for lease or sale.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="horse_details">Horse Details</label>
                </th>
                <td>
                    <textarea id="horse_details" name="horse_details" rows="2" class="large-text" 
                              placeholder="e.g., 13-year-old, 16.3h Zangersheide Gelding"><?php echo esc_textarea($horse_details); ?></textarea>
                    <p class="description">Age, height, breed, and other basic details.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="horse_description">Description</label>
                </th>
                <td>
                    <p class="description" style="margin-bottom: 8px; color: #0073aa; font-weight: bold;">
                        💡 <strong>Markdown Support:</strong> Use # for large headers or ## for medium headers on the first line to create section titles in green.
                    </p>
                    <textarea id="horse_description" name="horse_description" rows="4" class="large-text" 
                              placeholder="Main description of the horse..."><?php echo esc_textarea($horse_description); ?></textarea>
                    <p class="description">Main description and qualities of the horse. You can use markdown headers (# or ##) to create section titles.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="horse_qualities">Additional Qualities</label>
                </th>
                <td>
                    <p class="description" style="margin-bottom: 8px; color: #0073aa; font-weight: bold;">
                        💡 <strong>Markdown Support:</strong> Use # for large headers or ## for medium headers on the first line to create section titles in green.
                    </p>
                    <textarea id="horse_qualities" name="horse_qualities" rows="3" class="large-text" 
                              placeholder="Additional qualities and characteristics..."><?php echo esc_textarea($horse_qualities); ?></textarea>
                    <p class="description">Additional qualities, experience, and characteristics. You can use markdown headers (# or ##) to create section titles.</p>
                </td>
            </tr>
            
            <!-- Lease-specific fields -->
            <tr class="lease-fields" style="display: <?php echo ($horse_type === 'lease') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="lease_details">Lease Details</label>
                </th>
                <td>
                    <p class="description" style="margin-bottom: 8px; color: #0073aa; font-weight: bold;">
                        💡 <strong>Markdown Support:</strong> Use # for large headers or ## for medium headers on the first line to create section titles in green.
                    </p>
                    <textarea id="lease_details" name="lease_details" rows="3" class="large-text" 
                              placeholder="Lease terms, conditions, and details..."><?php echo esc_textarea($lease_details); ?></textarea>
                    <p class="description">Lease terms, conditions, and specific details. You can use markdown headers (# or ##) to create section titles.</p>
                </td>
            </tr>
            <tr class="lease-fields" style="display: <?php echo ($horse_type === 'lease') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="lease_price">Lease Price</label>
                </th>
                <td>
                    <input type="text" id="lease_price" name="lease_price" 
                           value="<?php echo esc_attr($lease_price); ?>" class="regular-text" 
                           placeholder="e.g., Mid-five figures for the year" />
                    <p class="description">Lease price or price range.</p>
                </td>
            </tr>
            <tr class="lease-fields" style="display: <?php echo ($horse_type === 'lease') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="lease_urgency">Urgency Note</label>
                </th>
                <td>
                    <input type="text" id="lease_urgency" name="lease_urgency" 
                           value="<?php echo esc_attr($lease_urgency); ?>" class="regular-text" 
                           placeholder="e.g., MUST BE LEASED BY JULY 10TH" />
                    <p class="description">Any urgency or deadline information.</p>
                </td>
            </tr>
            <tr class="lease-fields" style="display: <?php echo ($horse_type === 'lease') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="lease_location">Current Location</label>
                </th>
                <td>
                    <input type="text" id="lease_location" name="lease_location" 
                           value="<?php echo esc_attr($lease_location); ?>" class="regular-text" 
                           placeholder="e.g., Currently at Thunderbird Show Park (June/July)" />
                    <p class="description">Current location and availability for trials.</p>
                </td>
            </tr>
            
            <!-- Sale-specific fields -->
            <tr class="sale-fields" style="display: <?php echo ($horse_type === 'sale') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="sale_price">Sale Price</label>
                </th>
                <td>
                    <input type="text" id="sale_price" name="sale_price" 
                           value="<?php echo esc_attr($sale_price); ?>" class="regular-text" 
                           placeholder="e.g., $150,000" />
                    <p class="description">Sale price of the horse.</p>
                </td>
            </tr>
            <tr class="sale-fields" style="display: <?php echo ($horse_type === 'sale') ? 'table-row' : 'none'; ?>;">
                <th scope="row">
                    <label for="sale_details">Sale Details</label>
                </th>
                <td>
                    <p class="description" style="margin-bottom: 8px; color: #0073aa; font-weight: bold;">
                        💡 <strong>Markdown Support:</strong> Use # for large headers or ## for medium headers on the first line to create section titles in green.
                    </p>
                    <textarea id="sale_details" name="sale_details" rows="3" class="large-text" 
                              placeholder="Sale terms, conditions, and details..."><?php echo esc_textarea($sale_details); ?></textarea>
                    <p class="description">Sale terms, conditions, and specific details. You can use markdown headers (# or ##) to create section titles.</p>
                </td>
            </tr>
            
            <!-- Video fields -->
            <tr>
                <th scope="row">
                    <label for="video_url">Video URL (Optional)</label>
                </th>
                <td>
                    <input type="url" id="video_url" name="video_url" 
                           value="<?php echo esc_attr($video_url); ?>" class="regular-text" 
                           placeholder="https://example.com/video.mp4" />
                    <p class="description">URL to the horse's video (optional).</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="video_text">Video Button Text (Optional)</label>
                </th>
                <td>
                    <input type="text" id="video_text" name="video_text" 
                           value="<?php echo esc_attr($video_text); ?>" class="regular-text" 
                           placeholder="Video" />
                    <p class="description">Text for the video button (defaults to "Video" if empty).</p>
                </td>
            </tr>
        </table>
        
        <script>
        jQuery(document).ready(function($) {
            $('#horse_type').change(function() {
                var type = $(this).val();
                if (type === 'lease') {
                    $('.lease-fields').show();
                    $('.sale-fields').hide();
                } else if (type === 'sale') {
                    $('.lease-fields').hide();
                    $('.sale-fields').show();
                } else {
                    $('.lease-fields, .sale-fields').hide();
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Render the layout meta box
     */
    public function render_layout_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('save_layout_details', 'layout_details_nonce');
        
        // Get existing values
        $image_position = get_post_meta($post->ID, '_image_position', true);
        $display_order = get_post_meta($post->ID, '_display_order', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="image_position">Image Position</label>
                </th>
                <td>
                    <select id="image_position" name="image_position" class="regular-text">
                        <option value="left" <?php selected($image_position, 'left'); ?>>Left</option>
                        <option value="right" <?php selected($image_position, 'right'); ?>>Right</option>
                    </select>
                    <p class="description">Choose whether the image appears on the left or right.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="display_order">Display Order</label>
                </th>
                <td>
                    <input type="number" id="display_order" name="display_order" 
                           value="<?php echo esc_attr($display_order); ?>" class="small-text" 
                           min="1" placeholder="1" />
                    <p class="description">Order in which this horse appears (lower numbers appear first).</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save horse meta data
     */
    public function save_horses_meta($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['horse_details_nonce']) || 
            !wp_verify_nonce($_POST['horse_details_nonce'], 'save_horse_details')) {
            return;
        }
        
        if (!isset($_POST['layout_details_nonce']) || 
            !wp_verify_nonce($_POST['layout_details_nonce'], 'save_layout_details')) {
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
        
        // Save horse details
        $fields = array(
            'horse_type', 'horse_details', 'horse_description', 'horse_qualities',
            'lease_details', 'lease_price', 'lease_urgency', 'lease_location',
            'sale_price', 'sale_details', 'video_url', 'video_text',
            'image_position', 'display_order'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                // Use sanitize_textarea_field for fields that need to preserve newlines
                if (in_array($field, array('horse_description', 'horse_qualities', 'lease_details', 'sale_details'))) {
                    update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
                } else {
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'tiffany_horse') {
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }
    }
}

// Initialize the plugin
new LeaseSaleHorsesPlugin();

// Add custom columns to horses list
add_filter('manage_tiffany_horse_posts_columns', function($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['horse_type'] = 'Type';
    $new_columns['price'] = 'Price';
    $new_columns['display_order'] = 'Order';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
});

// Populate custom columns
add_action('manage_tiffany_horse_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'horse_type':
            $type = get_post_meta($post_id, '_horse_type', true);
            echo ucfirst($type);
            break;
        case 'price':
            $type = get_post_meta($post_id, '_horse_type', true);
            if ($type === 'lease') {
                echo get_post_meta($post_id, '_lease_price', true);
            } elseif ($type === 'sale') {
                echo get_post_meta($post_id, '_sale_price', true);
            }
            break;
        case 'display_order':
            echo get_post_meta($post_id, '_display_order', true);
            break;
    }
}, 10, 2);

// Make columns sortable
add_filter('manage_edit-tiffany_horse_sortable_columns', function($columns) {
    $columns['horse_type'] = 'horse_type';
    $columns['display_order'] = 'display_order';
    return $columns;
}); 