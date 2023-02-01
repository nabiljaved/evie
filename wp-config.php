<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '7j[j-:k,<!%FkOQ1DFn`E,?a1P$L^o4XOB&R~o[]E,}~yDx-r9c9FK:iU~x@+=OW' );
define( 'SECURE_AUTH_KEY',  '%4Qh/15={hAek4*5$.C/tg-wq$FNwY?MCjfiA5|&HD).#zV^(_5[2k4w~Qbun=V_' );
define( 'LOGGED_IN_KEY',    'Z]kH{w_%^NrE:^O?ypv PO4>K5QpRU?N6Tg]x?] &tyCj{Tve_8gMSE5Ttpg/`iC' );
define( 'NONCE_KEY',        '-d&U>g dOco?Y,<_41Cdac$GeAd)#5k~y|wyco6SL^/S8.z)%RcZsq@03oi`BqnW' );
define( 'AUTH_SALT',        'Zt2+enGqG2BZ>Q?Ka5EPSQcR/N|uN{@cyFBHmb7W(Jr*W$ycz_SmUl[_^lKk{)*1' );
define( 'SECURE_AUTH_SALT', '!.2z,BaiF]I7`G[mAXkF$gB4DVN!CQJb` )5p*!eo=sW:^2fx%V3B5+u&1? M6U3' );
define( 'LOGGED_IN_SALT',   '9[n;X%=7Czbv+bn0l2`J3U1?R]*Kb]}!]ATde4<0{2+@z._e>AT/W=iDAE~&OwJ&' );
define( 'NONCE_SALT',       '-YC!GkT|5M?YW3RS:QY#=j,0c$L^ N&P;Xr(c2#t:i<-5Y(C|W,^B<?*)`$?>[Ig' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
