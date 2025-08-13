<?php
/**
 * Plugin Name: Tiffany Gallery
 * Description: Manage gallery images for Tiffany & Equestrians website with ordering capabilities
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

if (!defined('ABSPATH')) {
    exit;
}

class TiffanyGalleryPlugin {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_filter('manage_tiffany_gallery_posts_columns', [$this, 'add_admin_columns']);
        add_action('manage_tiffany_gallery_posts_custom_column', [$this, 'populate_admin_columns'], 10, 2);
        add_filter('manage_edit-tiffany_gallery_sortable_columns', [$this, 'make_columns_sortable']);
        add_action('pre_get_posts', [$this, 'set_default_admin_order']);
    }

    public function register_cpt() {
        $labels = [
            'name' => 'Gallery Images',
            'singular_name' => 'Gallery Image',
            'menu_name' => 'Gallery',
            'add_new' => 'Add New Image',
            'add_new_item' => 'Add New Image',
            'edit_item' => 'Edit Image',
            'new_item' => 'New Image',
            'view_item' => 'View Image',
            'search_items' => 'Search Images',
            'not_found' => 'No images found',
            'not_found_in_trash' => 'No images found in trash'
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'thumbnail'],
            'menu_position' => 24,
            'menu_icon' => 'dashicons-format-gallery',
            'orderby' => 'meta_value_num',
            'meta_key' => '_display_order',
            'order' => 'ASC',
        ];

        register_post_type('tiffany_gallery', $args);
    }

    public function add_meta_boxes() {
        add_meta_box('gallery_details', 'Gallery Details', [$this, 'render_details_box'], 'tiffany_gallery', 'normal', 'high');
    }

    public function render_details_box($post) {
        wp_nonce_field('save_gallery', 'gallery_nonce');
        $display_order = get_post_meta($post->ID, '_display_order', true);
        $caption = get_post_meta($post->ID, '_caption', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="display_order">Display Order</label></th>
                <td>
                    <input type="number" id="display_order" name="display_order" class="small-text" value="<?php echo esc_attr($display_order); ?>" min="1" placeholder="1" />
                    <p class="description">Order in which this image appears (lower numbers appear first).</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="caption">Caption</label></th>
                <td>
                    <textarea id="caption" name="caption" rows="3" class="large-text" placeholder="Optional caption for the image..."><?php echo esc_textarea($caption); ?></textarea>
                    <p class="description">Optional caption that will appear below the image.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Gallery Image</th>
                <td>
                    <p class="description">Use the WordPress "Featured image" box to set the gallery image.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta($post_id) {
        if (!isset($_POST['gallery_nonce']) || !wp_verify_nonce($_POST['gallery_nonce'], 'save_gallery')) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (!current_user_can('edit_post', $post_id)) { return; }

        if (isset($_POST['display_order'])) {
            update_post_meta($post_id, '_display_order', intval($_POST['display_order']));
        }
        if (isset($_POST['caption'])) {
            update_post_meta($post_id, '_caption', sanitize_textarea_field($_POST['caption']));
        }
    }

    public function enqueue_admin($hook) {
        global $post_type;
        if ($post_type === 'tiffany_gallery') {
            wp_enqueue_media();
            
            // Add custom CSS for admin columns
            wp_add_inline_style('wp-admin', '
                .column-thumbnail { width: 100px; }
                .column-display_order { width: 80px; text-align: center; }
                .column-caption { width: 200px; }
                .column-gallery_location { width: 150px; }
                .column-thumbnail img { border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                .column-display_order strong { background: #0073aa; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; }
            ');
        }
    }

    public function add_admin_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb']; // Checkbox
        $new_columns['thumbnail'] = 'Image';
        $new_columns['title'] = $columns['title']; // Title
        $new_columns['display_order'] = 'Order';
        $new_columns['caption'] = 'Caption';
        $new_columns['gallery_location'] = 'Gallery Location';
        $new_columns['date'] = $columns['date']; // Date
        
        return $new_columns;
    }

    public function populate_admin_columns($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
                    echo '<div style="max-width: 80px;">' . $thumbnail . '</div>';
                } else {
                    echo '<span style="color: #999;">No image</span>';
                }
                break;
                
            case 'display_order':
                $order = get_post_meta($post_id, '_display_order', true);
                if ($order) {
                    echo '<strong>' . esc_html($order) . '</strong>';
                } else {
                    echo '<span style="color: #999;">Not set</span>';
                }
                break;
                
            case 'caption':
                $caption = get_post_meta($post_id, '_caption', true);
                if ($caption) {
                    echo '<div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' . esc_attr($caption) . '">' . esc_html($caption) . '</div>';
                } else {
                    echo '<span style="color: #999;">No caption</span>';
                }
                break;
                
            case 'gallery_location':
                $order = get_post_meta($post_id, '_display_order', true);
                if ($order) {
                    echo '<span style="color: #666;">Position ' . esc_html($order) . ' in gallery</span>';
                } else {
                    echo '<span style="color: #999;">Not positioned</span>';
                }
                break;
        }
    }

    public function make_columns_sortable($columns) {
        $columns['display_order'] = 'display_order';
        return $columns;
    }

    public function set_default_admin_order($query) {
        if (!is_admin()) {
            return;
        }
        
        global $pagenow, $post_type;
        
        if ($pagenow === 'edit.php' && $post_type === 'tiffany_gallery' && !isset($_GET['orderby'])) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_display_order');
            $query->set('order', 'ASC');
        }
    }
}

new TiffanyGalleryPlugin();

// Make the function globally available
function get_tiffany_gallery($limit = -1) {
    $query = new WP_Query([
        'post_type' => 'tiffany_gallery',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all images first
        'orderby' => 'meta_value_num',
        'meta_key' => '_display_order',
        'order' => 'ASC',
    ]);
    $gallery = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) { $query->the_post();
            $id = get_the_ID();
            $display_order = get_post_meta($id, '_display_order', true);
            $gallery[] = [
                'title' => get_the_title(),
                'caption' => get_post_meta($id, '_caption', true),
                'image' => get_the_post_thumbnail_url($id, 'large'),
                'thumbnail' => get_the_post_thumbnail_url($id, 'medium'),
                'order' => $display_order ? intval($display_order) : 999,
            ];
        }
        wp_reset_postdata();
        
        // Sort by order to ensure proper sequence
        usort($gallery, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        // Apply limit after sorting
        if ($limit > 0) {
            $gallery = array_slice($gallery, 0, $limit);
        }
    }
    return $gallery;
}
