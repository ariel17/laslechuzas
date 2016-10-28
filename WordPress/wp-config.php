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
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/laslechuzas/site/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
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
define('AUTH_KEY',         '[$`1[roKl+Cfeo}drR%xk(jLc:y&8{Hc$HIa-=&;+B+F.}D5BK|xUU@Eg-,8)-cg');
define('SECURE_AUTH_KEY',  ']h<*B#lc_lH#kxJ!25q-I/EJfr[vM3pJ7q$k]hGmP@>Qyg `H}m1x@>|!n2M2G6.');
define('LOGGED_IN_KEY',    'UO5+@L%Z~9wW~*&23Jz{`SA%&]l@Irq_|kF&h>.c?T*K?,Hg<?|UM@>i2L8G<,ZU');
define('NONCE_KEY',        'f3}03=0:+}(OZj|7gT,&9wpFZI4d( L=gcAb0,^TGX*hZ[-<D _5fSyg|>|aKxjw');
define('AUTH_SALT',        'i<S`X>64j$df{)et6<+c>vNf-PvPF+0FJC^|$|e;3]<5[VRV9T6|bjIE+7|8)ll#');
define('SECURE_AUTH_SALT', 'UK<W/fv0&-GW~@k(8V6Jq:sy$r~cfrtK:=w+N4Z>` 0-NVy4O{fi,Dpy@/n@vm9b');
define('LOGGED_IN_SALT',   'z-h[@w)y0jM/K5|Q$YjAH)Gt|m5vkxSt:ajpI5q__Kzk<ETZ +SGLZ:B&DV#z.Li');
define('NONCE_SALT',       'SAjkzXidQmPm19lhvCyW+wt-!!2bK+DsR6@b{kT],qeb)DkW31N#R._l/I@l ?{~');


/**#@-*/

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


/** Overwrite URL prefix for Vagrant instance */

define('WP_HOME', 'http://www.laslechuzas.com.ar/');
define('WP_SITEURL', 'http://www.laslechuzas.com.ar/');
define('FS_METHOD', 'direct');
