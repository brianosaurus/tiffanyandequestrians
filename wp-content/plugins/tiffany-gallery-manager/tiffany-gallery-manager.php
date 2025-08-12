<?php
/**
 * Plugin Name: Tiffany & Equestrians Gallery Manager
 * Description: Manage gallery images in the root gallery directory
 * Version: 1.0.0
 * Author: Tiffany & Equestrians
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TiffanyGalleryManager {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_upload_gallery_image', array($this, 'upload_gallery_image'));
        add_action('wp_ajax_delete_gallery_image', array($this, 'delete_gallery_image'));
        add_action('wp_ajax_get_gallery_images', array($this, 'get_gallery_images'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Gallery Manager',
            'Gallery Manager',
            'manage_options',
            'tiffany-gallery-manager',
            array($this, 'render_admin_page'),
            'dashicons-format-gallery',
            22
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_tiffany-gallery-manager') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_style('tiffany-gallery-manager', plugin_dir_url(__FILE__) . 'css/admin.css');
        wp_enqueue_script('tiffany-gallery-manager', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('tiffany-gallery-manager', 'tiffanyGalleryAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tiffany_gallery_nonce')
        ));
    }
    
    /**
     * Render admin page
     */
         public function render_admin_page() {
         $gallery_dir = ABSPATH . 'gallery/';
         $gallery_url = site_url() . '/gallery/';
        
        // Ensure gallery directory exists
        if (!is_dir($gallery_dir)) {
            wp_mkdir_p($gallery_dir);
        }
        
        ?>
        <div class="wrap">
            <h1>Gallery Manager</h1>
            
            <div class="gallery-manager-container">
                <!-- Upload Section -->
                <div class="upload-section">
                    <h2>Upload New Image</h2>
                    <div class="upload-area" id="upload-area">
                        <div class="upload-prompt">
                            <span class="dashicons dashicons-upload"></span>
                            <p>Click to select image or drag and drop</p>
                            <input type="file" id="gallery-upload" accept="image/*" multiple style="display: none;">
                        </div>
                        <div class="upload-progress" style="display: none;">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <p class="progress-text">Uploading...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Images Section -->
                <div class="gallery-section">
                    <h2>Gallery Images</h2>
                    <div class="gallery-grid" id="gallery-grid">
                        <div class="loading">Loading images...</div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .gallery-manager-container {
            margin-top: 20px;
        }
        
        .upload-section {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        
        .upload-area:hover {
            border-color: #0073aa;
        }
        
        .upload-area.dragover {
            border-color: #0073aa;
            background-color: #f0f8ff;
        }
        
        .upload-prompt .dashicons {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 10px;
        }
        
        .upload-prompt p {
            margin: 0;
            color: #666;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #0073aa;
            width: 0%;
            transition: width 0.3s;
        }
        
        .gallery-section {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .gallery-item {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        
        .gallery-item-info {
            padding: 10px;
            background: #f9f9f9;
        }
        
        .gallery-item-name {
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-all;
        }
        
        .gallery-item-size {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .gallery-item-actions {
            display: flex;
            gap: 5px;
        }
        
        .gallery-item-actions button {
            flex: 1;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .btn-copy {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-copy:hover {
            background-color: #5a6268;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-gallery {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Load gallery images on page load
            loadGalleryImages();
            
            // Upload area click handler
            $('#upload-area').click(function() {
                $('#gallery-upload').click();
            });
            
            // File input change handler
            $('#gallery-upload').change(function() {
                var files = this.files;
                for (var i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            });
            
            // Drag and drop handlers
            $('#upload-area').on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });
            
            $('#upload-area').on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });
            
            $('#upload-area').on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                
                var files = e.originalEvent.dataTransfer.files;
                for (var i = 0; i < files.length; i++) {
                    if (files[i].type.startsWith('image/')) {
                        uploadImage(files[i]);
                    }
                }
            });
            
            function uploadImage(file) {
                var formData = new FormData();
                formData.append('action', 'upload_gallery_image');
                formData.append('nonce', tiffanyGalleryAjax.nonce);
                formData.append('image', file);
                
                $('.upload-progress').show();
                $('.upload-prompt').hide();
                
                $.ajax({
                    url: tiffanyGalleryAjax.ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                $('.progress-fill').css('width', percentComplete * 100 + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        if (response.success) {
                            loadGalleryImages();
                            $('.progress-fill').css('width', '0%');
                        } else {
                            alert('Upload failed: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Upload failed. Please try again.');
                    },
                    complete: function() {
                        $('.upload-progress').hide();
                        $('.upload-prompt').show();
                        $('#gallery-upload').val('');
                    }
                });
            }
            
            function loadGalleryImages() {
                $.ajax({
                    url: tiffanyGalleryAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_gallery_images',
                        nonce: tiffanyGalleryAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            displayGalleryImages(response.data);
                        } else {
                            $('#gallery-grid').html('<div class="empty-gallery">Failed to load images</div>');
                        }
                    },
                    error: function() {
                        $('#gallery-grid').html('<div class="empty-gallery">Failed to load images</div>');
                    }
                });
            }
            
            function displayGalleryImages(images) {
                if (images.length === 0) {
                    $('#gallery-grid').html('<div class="empty-gallery">No images found in gallery</div>');
                    return;
                }
                
                var html = '';
                images.forEach(function(image) {
                    html += '<div class="gallery-item">';
                    html += '<img src="' + image.url + '" alt="' + image.name + '">';
                    html += '<div class="gallery-item-info">';
                    html += '<div class="gallery-item-name">' + image.name + '</div>';
                    html += '<div class="gallery-item-size">' + image.size + '</div>';
                    html += '<div class="gallery-item-actions">';
                    html += '<button class="btn-copy" onclick="copyImageUrl(\'' + image.url + '\')">Copy URL</button>';
                    html += '<button class="btn-delete" onclick="deleteImage(\'' + image.name + '\')">Delete</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                });
                
                $('#gallery-grid').html(html);
            }
            
            // Global functions for button actions
            window.deleteImage = function(imageName) {
                if (confirm('Are you sure you want to delete "' + imageName + '"?')) {
                    $.ajax({
                        url: tiffanyGalleryAjax.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'delete_gallery_image',
                            nonce: tiffanyGalleryAjax.nonce,
                            image_name: imageName
                        },
                        success: function(response) {
                            if (response.success) {
                                loadGalleryImages();
                            } else {
                                alert('Delete failed: ' + response.data);
                            }
                        },
                        error: function() {
                            alert('Delete failed. Please try again.');
                        }
                    });
                }
            };
            
            window.copyImageUrl = function(url) {
                navigator.clipboard.writeText(url).then(function() {
                    alert('Image URL copied to clipboard!');
                }).catch(function() {
                    // Fallback for older browsers
                    var textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Image URL copied to clipboard!');
                });
            };
        });
        </script>
        <?php
    }
    
    /**
     * Upload gallery image
     */
    public function upload_gallery_image() {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'tiffany_gallery_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error('No file uploaded or upload error');
        }
        
        $file = $_FILES['image'];
        $gallery_dir = ABSPATH . 'gallery/';
        
        // Ensure gallery directory exists
        if (!is_dir($gallery_dir)) {
            wp_mkdir_p($gallery_dir);
        }
        
        // Validate file type
        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');
        if (!in_array($file['type'], $allowed_types)) {
            wp_send_json_error('Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.');
        }
        
        // Generate unique filename
        $filename = sanitize_file_name($file['name']);
        $path_parts = pathinfo($filename);
        $base_name = $path_parts['filename'];
        $extension = $path_parts['extension'];
        $counter = 1;
        
        while (file_exists($gallery_dir . $filename)) {
            $filename = $base_name . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        // Move uploaded file
        $destination = $gallery_dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            wp_send_json_success('Image uploaded successfully');
        } else {
            wp_send_json_error('Failed to move uploaded file');
        }
    }
    
    /**
     * Delete gallery image
     */
    public function delete_gallery_image() {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'tiffany_gallery_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $image_name = sanitize_file_name($_POST['image_name']);
        $gallery_dir = ABSPATH . 'gallery/';
        $file_path = $gallery_dir . $image_name;
        
        // Validate file exists and is in gallery directory
        if (!file_exists($file_path) || !is_file($file_path)) {
            wp_send_json_error('File not found');
        }
        
        // Check if file is actually in the gallery directory (security)
        $real_path = realpath($file_path);
        $real_gallery_dir = realpath($gallery_dir);
        if (strpos($real_path, $real_gallery_dir) !== 0) {
            wp_send_json_error('Invalid file path');
        }
        
        // Delete file
        if (unlink($file_path)) {
            wp_send_json_success('Image deleted successfully');
        } else {
            wp_send_json_error('Failed to delete file');
        }
    }
    
    /**
     * Get gallery images
     */
         public function get_gallery_images() {
         // Check nonce
         if (!wp_verify_nonce($_POST['nonce'], 'tiffany_gallery_nonce')) {
             wp_die('Security check failed');
         }
         
         // Check permissions
         if (!current_user_can('manage_options')) {
             wp_die('Insufficient permissions');
         }
         
         $gallery_dir = ABSPATH . 'gallery/';
         $gallery_url = site_url() . '/gallery/';
        $images = array();
        
        if (is_dir($gallery_dir)) {
            $files = scandir($gallery_dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                
                $file_path = $gallery_dir . $file;
                if (is_file($file_path)) {
                    $file_info = pathinfo($file);
                    $extension = strtolower($file_info['extension']);
                    
                    // Only include image files
                    if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
                        $images[] = array(
                            'name' => $file,
                            'url' => $gallery_url . $file,
                            'size' => size_format(filesize($file_path)),
                            'path' => $file_path
                        );
                    }
                }
            }
        }
        
        // Sort by filename
        usort($images, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        
        wp_send_json_success($images);
    }
}

// Initialize the plugin
new TiffanyGalleryManager(); 