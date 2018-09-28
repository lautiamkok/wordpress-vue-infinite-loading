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
define('DB_NAME', 'wordpress-vue-infinite');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'tklau');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '(K;QWK8}S<C_/s}=c]J@)?z.9R1]~HD.Im2 N=Ld- 5P|SwqRk$<!QB!LWLVQwgV');
define('SECURE_AUTH_KEY',  '}H)t+[G!0=jDQX-9QyzbY39[gR:XDS.>|M$Eacyb4[1a<-@n|2kL7 s{t1;8{_kR');
define('LOGGED_IN_KEY',    '0zvr?c09M-_TWvOA=2!K 3q{)j&G]G-FN]?##],wG#u!g~hZvWru0t<B3J^UsB{Q');
define('NONCE_KEY',        ']MYf1]W2r&W+},CI?X]}nfh_r:7{MS+YblC3@Z>-EGLM3+ fpES?%s#qHgOkKO]%');
define('AUTH_SALT',        ': qU-1uC~YlOQ`@T&-J@(h_cD)UO^}&$4uRp]e2vr-&=} Io~X/lO,c(?:r6eq4N');
define('SECURE_AUTH_SALT', 'q|E/rmzfP#sC}>f]w7#9X20`Gf+JToYSf-9OD!Btu9 a82[kMQ;gPiSb-g0*q|:R');
define('LOGGED_IN_SALT',   'VO#~{v=Z:<8GM$HA9Wq;OU~$o*+1//-^Injll/$>`R8(XUAmJMSPKr3/o&yf2>^M');
define('NONCE_SALT',       '^a!h^x;avez{}7TI`;=$`Z@~llypY$jR_Er.DxmSgM:/q#=U=_E_._Wbp1bxOCyD');

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
