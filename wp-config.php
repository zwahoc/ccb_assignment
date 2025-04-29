<?php



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
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\xampp\htdocs\tarumtgradhub\wp-content\plugins\wp-super-cache/' );
define('DB_NAME', 'rds_database');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'localhost:3307');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY', '$@dcNUb+#W$[m2>{MwzIwr8D//:fqBE>wwMfD~0Xd$Z^Ss{[a%J>7jXocu~W{Pnu');
define('SECURE_AUTH_KEY', '}gA0.>K/#Zp$ZwA[=EL5;}S!!OOW4u8nf7~$71d_X9`AAm<`o<K5z%s_ZA,=@/W-');
define('LOGGED_IN_KEY', '7lo2mx]^o*at)ZF9Kl(OQr`=;Dg`<g_xbwO`0vxcPf=06Tn$>cQ&N=S^d)1V%+:D');
define('NONCE_KEY', '`lo=]Cu{o-mWYj&p*k~3a7V0B`fjZ:0`jmx~`}}6=jo3M)6/w}%HirdVs][ 8b`J');
define('AUTH_SALT', '~},KEF6=aq{m3XSol<o5wP&[nO%RSrW&6W&fwL$iBMM.snDwbGc%&c&nQaH.Btig');
define('SECURE_AUTH_SALT', 'E^mR?{[bwkyvsfqo6k~h]VwV,b4Ff;,4Vw.vm1J,h}~va]S&g5XW:bA~Ru_/wA^D');
define('LOGGED_IN_SALT', 'khy3@HCuaO5etw~fLkIT$ZP;;7b/Mt -PUrFG|a`xv?j)?pQcB3;1_9_r0]S^J[C');
define('NONCE_SALT', ',8.0]4$&2R_Nc>tq~=mt2&q,m/yI;MJ_kqL.1JX]|W$J{y:~a:p{#~)qDG+F8PCK');

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
$table_prefix = 'wp_';

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
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */

// ✨ Temporary force site URL and home URL for ngrok
// if (isset($_SERVER['HTTP_HOST'])) {
//     define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] . '/ccb_assignment');
//     define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/ccb_assignment');
//     $_SERVER['HTTPS'] = 'on'; // <== force WordPress to treat it as HTTPS
// }





/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
