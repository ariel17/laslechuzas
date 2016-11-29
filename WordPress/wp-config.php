<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'laslechuzas');

/** MySQL database username */
define('DB_USER', 'laslechuzas');

/** MySQL database password */
define('DB_PASSWORD', 'p3p4p111g#');

/** MySQL hostname */
define('DB_HOST', 'db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '5kiEb3A P77YS5B2+&h9IBr3e,T+Z{JGT@UK),G7MR-oBVZ]dGO@-cb3#--<4*|!');
define('SECURE_AUTH_KEY',  'wyq0>ja{B}r+ACt,L!;|Cd6*+kYhd]k4v:s|}OSHIk.TzCOLfF;HYT(Vk*E3?5Lh');
define('LOGGED_IN_KEY',    '45qcE^4}*UaK*qr?.Ju4Nw|:~~-+)g|4r?SOL$%,B3<xv+TnNLHU~eSuMxv=0|8F');
define('NONCE_KEY',        '+(`kLlOw3]n955HJ<xR253^ha!EcEX)=Jnz&>,CD4Y&s,I;vbL-<f(|AM*i3>a=5');
define('AUTH_SALT',        '^y0RRc[4-A{n,6%+)cHo+%D>m4/Bg skq0TKn2{a/g_=+(s1ht-7Ad+6%cgz6rtu');
define('SECURE_AUTH_SALT', 'X]hl:O2G&LT-2<SLxbB_sV[C|tzS*^7oqAeU2fq[+3-[+TN|Y*7lRm`inoxQ*#TC');
define('LOGGED_IN_SALT',   'aTh7#7 +0~VVo!Q%Pi4IPBf(@P a(;I;aeCT4cj<ndS7V=aOELdDlB9aq|t(@#+,');
define('NONCE_SALT',       ':p*Q5?USBCF)Ai+n7w(xWB?vu:LaX~kP.IIg@USc^)+n@AUK2pT,{DRmDWmYv=+B');/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
