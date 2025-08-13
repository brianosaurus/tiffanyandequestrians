<?php
/**
 * Plugin Name: Tiffany Trainers
 * Description: Manage trainers for Tiffany & Equestrians website
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

if (!defined('ABSPATH')) {
    exit;
}

class TiffanyTrainersPlugin {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
    }

    public function register_cpt() {
        $labels = [
            'name' => 'Trainers',
            'singular_name' => 'Trainer',
            'menu_name' => 'Trainers',
            'add_new' => 'Add New Trainer',
            'add_new_item' => 'Add New Trainer',
            'edit_item' => 'Edit Trainer',
            'new_item' => 'New Trainer',
            'view_item' => 'View Trainer',
            'search_items' => 'Search Trainers',
            'not_found' => 'No trainers found',
            'not_found_in_trash' => 'No trainers found in trash'
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_position' => 23,
            'menu_icon' => 'dashicons-groups',
        ];

        register_post_type('tiffany_trainer', $args);
    }

    public function add_meta_boxes() {
        add_meta_box('trainer_details', 'Trainer Details', [$this, 'render_details_box'], 'tiffany_trainer', 'normal', 'high');
    }

    public function render_details_box($post) {
        wp_nonce_field('save_trainer', 'trainer_nonce');
        $title = get_post_meta($post->ID, '_title', true);
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="title">Title</label></th>
                <td>
                    <input type="text" id="title" name="title" class="regular-text" value="<?php echo esc_attr($title); ?>" placeholder="e.g., Owner & Head Trainer" />
                    <p class="description">Enter the trainer's title (e.g., "Owner & Head Trainer")</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Featured Image</th>
                <td>
                    <p class="description">Use the WordPress "Featured image" box to set the trainer's photo.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Full Biography</th>
                <td>
                    <p class="description">Use the main content editor above for the full trainer biography.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta($post_id) {
        if (!isset($_POST['trainer_nonce']) || !wp_verify_nonce($_POST['trainer_nonce'], 'save_trainer')) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (!current_user_can('edit_post', $post_id)) { return; }

        if (isset($_POST['title'])) {
            update_post_meta($post_id, '_title', sanitize_text_field($_POST['title']));
        }
    }

    public function enqueue_admin($hook) {
        global $post_type;
        if ($post_type === 'tiffany_trainer') {
            wp_enqueue_media();
        }
    }
}

new TiffanyTrainersPlugin();

// Make the function globally available
function get_tiffany_trainers() {
    $query = new WP_Query([
        'post_type' => 'tiffany_trainer',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $trainers = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) { $query->the_post();
            $id = get_the_ID();
            $trainers[] = [
                'title' => get_the_title(),
                'title_meta' => get_post_meta($id, '_title', true),
                'content' => get_post_field('post_content', $id),
                'image' => get_the_post_thumbnail_url($id, 'large'),
            ];
        }
        wp_reset_postdata();
    }
    return $trainers;
}


