    <?php
    require __DIR__ . '/wp-load.php';
    update_option('home', 'http://localhost:8000');
    update_option('siteurl', 'http://localhost:8000');
    flush_rewrite_rules();
    echo 'Updated.';
