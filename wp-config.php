<?php
define('WP_CACHE', true); // Enable cache (can be adjusted per environment)

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cnthohmy_wp865' );

/** Database username */
define( 'DB_USER', 'cnthohmy_wp865' );

/** Database password */
define( 'DB_PASSWORD', 'S[9o-pB5Q3' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4qzodbtv1eulpag1vdqyj186wodjsisgwdmgirf4ibfpkyd4ncqo6fkadwxshymc' );
define( 'SECURE_AUTH_KEY',  'ewmnxsequb8jd2hjvkvoyp03ckwgbxaidjhq6cjsdsejpkxfi3jvsk0uwaneokgx' );
define( 'LOGGED_IN_KEY',    '9y2tk3tfiikhwqg7pp7q8qy58qapciv0yyywagsxu0y3h6zp5fco9gkyacy88h92' );
define( 'NONCE_KEY',        'qlr2x6zgr3vvixmowb50h06urdqyacbxjnmqywnimuxudzoom46i9nms221c0ljl' );
define( 'AUTH_SALT',        'qwexmpytjpjxfgi4vkeomxa37rwxmd3fsmcg5zfojmo1edi5jgte90xrio9xdhse' );
define( 'SECURE_AUTH_SALT', 'fetmwb9grof5spayxkxfya0ixux3jxrzqqcr5n5ugdaoh0ukiic8wizec3gr0ps9' );
define( 'LOGGED_IN_SALT',   'sudbumkttxqkwpsnyccwqypgepll8jaw9hw28axgjkhxw8xqdjcmsvhxytazop27' );
define( 'NONCE_SALT',       'nuy0zezbja0ax5ee6plzekrpyj3oikbxqoal2pqqws3usbhydtbfipx1viz1lgpb' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp8h_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

// Dynamic WordPress URLs (no hardcoded domain/port). Works behind proxies too.
// For production environments, you can override this by defining WP_HOME and WP_SITEURL above
// Example: define('WP_HOME', 'https://yourdomain.com'); define('WP_SITEURL', 'https://yourdomain.com');
if (!defined('WP_HOME') || !defined('WP_SITEURL')) {
    // Honor proxy headers if present
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $_SERVER['HTTPS'] = $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ? 'on' : 'off';
    }
    $is_ssl = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')
    );
    $scheme = $is_ssl ? 'https://' : 'http://';
    
    // Get host from server variables
    $host = null;
    if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } elseif (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME'])) {
        $host = $_SERVER['SERVER_NAME'];
        // Add port if specified
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] !== '80' && $_SERVER['SERVER_PORT'] !== '443') {
            $host .= ':' . $_SERVER['SERVER_PORT'];
        }
    }
    
    // If we still don't have a host, use a sensible default for development
    if (!$host) {
        $host = 'localhost:8000'; // Default development port
    }
    
    $base_url = $scheme . $host;
    
    // Always define these constants to override database values
    define('WP_HOME', $base_url);
    define('WP_SITEURL', $base_url);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

// Ensure database options match current constants (runs after WordPress loads)
if (function_exists('get_option') && function_exists('update_option')) {
    $current_home = get_option('home');
    $current_siteurl = get_option('siteurl');
    $expected_home = WP_HOME;
    $expected_siteurl = WP_SITEURL;
    
    if ($current_home !== $expected_home || $current_siteurl !== $expected_siteurl) {
        update_option('home', $expected_home);
        update_option('siteurl', $expected_siteurl);
        // Flush rewrite rules to ensure proper URL generation
        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules();
        }
    }
}

