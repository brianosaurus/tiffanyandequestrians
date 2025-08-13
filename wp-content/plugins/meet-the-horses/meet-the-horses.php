<?php
/**
 * Plugin Name: Meet the Horses
 * Description: Manage horses shown on the Meet The Horses page (image, name, AKA, description)
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

if (!defined('ABSPATH')) {
    exit;
}

class MeetTheHorsesPlugin {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('pre_get_posts', [$this, 'set_default_admin_order']);
    }

    public function register_cpt() {
        $labels = [
            'name' => 'Meet the Horses',
            'singular_name' => 'Horse',
            'menu_name' => 'Meet the Horses',
            'add_new' => 'Add New Horse',
            'add_new_item' => 'Add New Horse',
            'edit_item' => 'Edit Horse',
            'new_item' => 'New Horse',
            'view_item' => 'View Horse',
            'search_items' => 'Search Horses',
            'not_found' => 'No horses found',
            'not_found_in_trash' => 'No horses found in trash'
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'thumbnail'],
            'menu_position' => 22,
            'menu_icon' => 'dashicons-format-image',
            'orderby' => 'meta_value_num',
            'meta_key' => '_display_order',
            'order' => 'ASC',
        ];

        register_post_type('meet_horse', $args);
    }

    public function add_meta_boxes() {
        add_meta_box('meet_horse_details', 'Horse Details', [$this, 'render_details_box'], 'meet_horse', 'normal', 'high');
    }

    public function render_details_box($post) {
        wp_nonce_field('save_meet_horse', 'meet_horse_nonce');
        $aka = get_post_meta($post->ID, '_aka', true);
        $description = get_post_meta($post->ID, '_description', true);
        $display_order = get_post_meta($post->ID, '_display_order', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="aka">Also Known As (AKA)</label></th>
                <td>
                    <input type="text" id="aka" name="aka" class="regular-text" value="<?php echo esc_attr($aka); ?>" placeholder="e.g., aka Bentley" />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="description">Description</label></th>
                <td>
                    <textarea id="description" name="description" rows="5" class="large-text" placeholder="Horse description..."><?php echo esc_textarea($description); ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">Featured Image</th>
                <td>
                    <p class="description">Use the WordPress "Featured image" box to set the horse image.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="display_order">Display Order</label></th>
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

    public function save_meta($post_id) {
        if (!isset($_POST['meet_horse_nonce']) || !wp_verify_nonce($_POST['meet_horse_nonce'], 'save_meet_horse')) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (!current_user_can('edit_post', $post_id)) { return; }

        if (isset($_POST['aka'])) {
            update_post_meta($post_id, '_aka', sanitize_text_field($_POST['aka']));
        }
        if (isset($_POST['description'])) {
            update_post_meta($post_id, '_description', sanitize_textarea_field($_POST['description']));
        }
                if (isset($_POST['display_order'])) {
            update_post_meta($post_id, '_display_order', intval($_POST['display_order']));
        }
    }
    
    /**
     * Set default admin order for horses list
     */
    public function set_default_admin_order($query) {
        if (!is_admin()) {
            return;
        }
        
        global $pagenow, $post_type;
        
        if ($pagenow === 'edit.php' && $post_type === 'meet_horse' && !isset($_GET['orderby'])) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_display_order');
            $query->set('order', 'ASC');
        }
    }
    
    public function enqueue_admin($hook) {
        global $post_type;
        if ($post_type === 'meet_horse') {
            wp_enqueue_media();
            
            // Add custom CSS for admin columns
            wp_add_inline_style('wp-admin', '
                .column-display_order { width: 80px; text-align: center; }
                .column-aka { width: 120px; }
                .column-description_excerpt { width: 200px; }
            ');
        }
    }
}

new MeetTheHorsesPlugin();

// Add custom columns to horses list
add_filter('manage_meet_horse_posts_columns', function($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['display_order'] = 'Order';
    $new_columns['aka'] = 'AKA';
    $new_columns['description_excerpt'] = 'Description';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
});

// Populate custom columns
add_action('manage_meet_horse_posts_custom_column', function($column, $post_id) {
    switch ($column) {
        case 'display_order':
            $order = get_post_meta($post_id, '_display_order', true);
            if ($order) {
                echo '<strong style="background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">' . esc_html($order) . '</strong>';
            } else {
                echo '<span style="color: #999;">Not set</span>';
            }
            break;
        case 'aka':
            echo esc_html(get_post_meta($post_id, '_aka', true));
            break;
        case 'description_excerpt':
            $description = get_post_meta($post_id, '_description', true);
            echo wp_trim_words($description, 10, '...');
            break;
    }
}, 10, 2);

// Make columns sortable
add_filter('manage_edit-meet_horse_sortable_columns', function($columns) {
    $columns['display_order'] = 'display_order';
    $columns['aka'] = 'aka';
    return $columns;
});

function get_meet_horses($limit = -1) {
    $query = new WP_Query([
        'post_type' => 'meet_horse',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => '_display_order',
        'order' => 'ASC',
    ]);
    $horses = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) { $query->the_post();
            $id = get_the_ID();
            $horses[] = [
                'id' => $id,
                'title' => get_the_title(),
                'aka' => get_post_meta($id, '_aka', true),
                'description' => get_post_meta($id, '_description', true),
                'image' => get_the_post_thumbnail_url($id, 'large'),
                'thumbnail' => get_the_post_thumbnail_url($id, 'medium'),
                'order' => get_post_meta($id, '_display_order', true) ?: 999,
            ];
        }
        wp_reset_postdata();
        
        // Sort by order to ensure proper sequence
        usort($horses, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        // Apply limit after sorting
        if ($limit > 0) {
            $horses = array_slice($horses, 0, $limit);
        }
    }
    return $horses;
}

