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
    }

    public function enqueue_admin($hook) {
        global $post_type;
        if ($post_type === 'meet_horse') {
            wp_enqueue_media();
        }
    }
}

new MeetTheHorsesPlugin();

function get_meet_horses() {
    $query = new WP_Query([
        'post_type' => 'meet_horse',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    $horses = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) { $query->the_post();
            $id = get_the_ID();
            $horses[] = [
                'title' => get_the_title(),
                'aka' => get_post_meta($id, '_aka', true),
                'description' => get_post_meta($id, '_description', true),
                'image' => get_the_post_thumbnail_url($id, 'large'),
            ];
        }
        wp_reset_postdata();
    }
    return $horses;
}

