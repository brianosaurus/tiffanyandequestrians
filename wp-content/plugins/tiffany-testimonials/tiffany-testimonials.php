<?php
/**
 * Plugin Name: Tiffany & Equestrians Testimonials Manager
 * Description: Manage testimonials with star ratings
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TiffanyTestimonialsManager {
    
    public function __construct() {
        add_action('init', array($this, 'register_testimonials_post_type'));
        add_action('add_meta_boxes', array($this, 'add_testimonials_meta_boxes'));
        add_action('save_post', array($this, 'save_testimonials_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'manage_testimonial_columns'), 10, 2);
        add_filter('manage_testimonial_posts_columns', array($this, 'add_testimonial_columns'));
    }
    
    /**
     * Register custom post type for testimonials
     */
    public function register_testimonials_post_type() {
        $labels = array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
            'menu_name' => 'Testimonials',
            'add_new' => 'Add New Testimonial',
            'add_new_item' => 'Add New Testimonial',
            'edit_item' => 'Edit Testimonial',
            'new_item' => 'New Testimonial',
            'view_item' => 'View Testimonial',
            'search_items' => 'Search Testimonials',
            'not_found' => 'No testimonials found',
            'not_found_in_trash' => 'No testimonials found in trash'
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'testimonials'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 23,
            'menu_icon' => 'dashicons-format-quote',
            'supports' => array('title'),
            'show_in_rest' => true
        );
        
        register_post_type('testimonial', $args);
    }
    
    /**
     * Add custom meta boxes for testimonial details
     */
    public function add_testimonials_meta_boxes() {
        add_meta_box(
            'testimonial_details',
            'Testimonial Details',
            array($this, 'render_testimonial_details_meta_box'),
            'testimonial',
            'normal',
            'high'
        );
    }
    
    /**
     * Render the testimonial details meta box
     */
    public function render_testimonial_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('save_testimonial_details', 'testimonial_details_nonce');
        
        // Get existing values
        $testimonial_text = get_post_meta($post->ID, '_testimonial_text', true);
        $author_name = get_post_meta($post->ID, '_author_name', true);
        $star_rating = get_post_meta($post->ID, '_star_rating', true);
        
        ?>
        <style>
        .star-rating-select {
            font-size: 16px;
            padding: 5px;
            width: 100px;
        }
        .testimonial-field {
            margin-bottom: 20px;
        }
        .testimonial-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .testimonial-field .description {
            font-style: italic;
            color: #666;
            margin-top: 5px;
        }
        .testimonial-text {
            width: 100%;
            min-height: 150px;
        }
        .author-name {
            width: 100%;
            max-width: 400px;
        }
        </style>
        
        <div class="testimonial-field">
            <label for="testimonial_text">Testimonial Text</label>
            <textarea id="testimonial_text" name="testimonial_text" class="testimonial-text"
                      placeholder="Enter the testimonial text here..."><?php echo esc_textarea($testimonial_text); ?></textarea>
            <p class="description">The main testimonial text. This will be displayed in quotes on the website.</p>
        </div>
        
        <div class="testimonial-field">
            <label for="author_name">Author Name</label>
            <input type="text" id="author_name" name="author_name" class="author-name" 
                   value="<?php echo esc_attr($author_name); ?>" 
                   placeholder="Enter the name of the person giving the testimonial" />
            <p class="description">The name of the person who provided this testimonial.</p>
        </div>
        
        <div class="testimonial-field">
            <label for="star_rating">Star Rating</label>
            <select id="star_rating" name="star_rating" class="star-rating-select">
                <option value="">Select Rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php selected($star_rating, $i); ?>>
                        <?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i); ?> (<?php echo $i; ?> stars)
                    </option>
                <?php endfor; ?>
            </select>
            <p class="description">Select the star rating for this testimonial (1-5 stars).</p>
        </div>
        <?php
    }
    
    /**
     * Save testimonial meta data
     */
    public function save_testimonials_meta($post_id) {
        // Check if nonce is valid
        if (!isset($_POST['testimonial_details_nonce']) || 
            !wp_verify_nonce($_POST['testimonial_details_nonce'], 'save_testimonial_details')) {
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
        
        // Save testimonial details
        if (isset($_POST['testimonial_text'])) {
            update_post_meta($post_id, '_testimonial_text', sanitize_textarea_field($_POST['testimonial_text']));
        }
        
        if (isset($_POST['author_name'])) {
            update_post_meta($post_id, '_author_name', sanitize_text_field($_POST['author_name']));
        }
        
        if (isset($_POST['star_rating'])) {
            $rating = intval($_POST['star_rating']);
            if ($rating >= 1 && $rating <= 5) {
                update_post_meta($post_id, '_star_rating', $rating);
            }
        }
    }
    
    /**
     * Add custom columns to testimonials list
     */
    public function add_testimonial_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = 'Title';
        $new_columns['author_name'] = 'Author';
        $new_columns['star_rating'] = 'Rating';
        $new_columns['testimonial_excerpt'] = 'Testimonial';
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    /**
     * Manage custom column content
     */
    public function manage_testimonial_columns($column, $post_id) {
        switch ($column) {
            case 'author_name':
                echo esc_html(get_post_meta($post_id, '_author_name', true));
                break;
                
            case 'star_rating':
                $rating = get_post_meta($post_id, '_star_rating', true);
                if ($rating) {
                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                }
                break;
                
            case 'testimonial_excerpt':
                $text = get_post_meta($post_id, '_testimonial_text', true);
                echo wp_trim_words($text, 10, '...');
                break;
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'testimonial') {
            wp_enqueue_style('tiffany-testimonials', plugin_dir_url(__FILE__) . 'css/admin.css');
        }
    }
}

// Initialize the plugin
new TiffanyTestimonialsManager();

/**
 * Function to get testimonials for display
 */
function get_tiffany_testimonials() {
    $args = array(
        'post_type' => 'testimonial',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $testimonials = array();
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $testimonials[] = array(
                'id' => get_the_ID(),
                'text' => get_post_meta(get_the_ID(), '_testimonial_text', true),
                'author' => get_post_meta(get_the_ID(), '_author_name', true),
                'rating' => get_post_meta(get_the_ID(), '_star_rating', true)
            );
        }
        wp_reset_postdata();
    }
    
    return $testimonials;
}