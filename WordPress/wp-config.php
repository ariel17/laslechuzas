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
define('DB_NAME', getenv('DB_NAME'));

/** MySQL database username */
define('DB_USER', getenv('DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', getenv('DB_PASSWORD'));

/** MySQL hostname */
define('DB_HOST', getenv('DB_HOST'));

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
define('AUTH_KEY',         'Z(a*-O|sjkb+oz6K:r9y<Yo(P(B>Ytq]DU.+DM_D$6h7 Rgt!]*]+B,vRDv{FwwA');
define('SECURE_AUTH_KEY',  '<NVPrJL|&<^MLnXU-E:WLaG/6#.0H5v1r|VpN,j_a`k<2VI;-&ODb 3QUOK!A?wu');
define('LOGGED_IN_KEY',    'a0oABrm-C9ap>)[?e7OPc02lXJO[ k438&qrT)o;MlZP9OwvESs6Bq^UP2 G`tIH');
define('NONCE_KEY',        'dh:)hcN6@|0{3 9yPVaXe+-[xH?KHSF=LGrI^;4Oo$w&_eFOHV[& bCyR9_!rrX_');
define('AUTH_SALT',        'P;hn%dt5W=R?U.Lh5@inb39-V@,n^1v+W>!F@F.EqeW$:RlP_`mo6L[~SBqcv]-(');
define('SECURE_AUTH_SALT', '9jo/UMirxS#jn:uUC&rmgN|>1m]5eW[4s@:3xN?!CL--(j<*fqF:c|Ii|`QP$s?P');
define('LOGGED_IN_SALT',   '9ZY<,SPBx%aR#H`!-hVPOmQ+2x P#f%_g=i|aIdIx)+9_0dm}> S$La!,%d)zubG');
define('NONCE_SALT',       ':wzQk|~5(r=(*BI/?#0<+Eus4@mIEYXxs!FbTr~h2a?3uQ3Qc9v=Xu 9=Hm8WQs}');

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
define('WP_DEBUG', getenv('WP_DEBUG'));

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
