<?php
define( 'WP_CACHE', true );
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u544102255_zfTs9' );

/** Database username */
define( 'DB_USER', 'u544102255_ZYjDd' );

/** Database password */
define( 'DB_PASSWORD', 'S53YZMRd34' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          'r9?/o9xg_iciuTtu45?w79;qA!4`.m;8~{tej5lg(&l{7*^N]?W#t*cj/2[h%)Hg' );
define( 'SECURE_AUTH_KEY',   'tNXiB0_vY?P*^G>>R@.TrJ#W,wCPb@4~eIar^h(s_U5QNSIHL]4O^8p47$$S^y{{' );
define( 'LOGGED_IN_KEY',     '_zK`RC&U%v@81E1I770h5`LA=n,KZg%N]mFC{#i4rk.m02`cFpol-*$9*v?;HE+[' );
define( 'NONCE_KEY',         'lO8vT%v,OliKNV)Q8y4~)t;R-BTtcDI]XoC2 i2x1Ln^G@7{zhc2j5 ~4TvYe|Mg' );
define( 'AUTH_SALT',         ',Cc<,~|+W,T7l51uVqKMlQAT,w!/W5K@Nv^xNxHX}8#F2N@Dkhgl!%<~0@-9+zj-' );
define( 'SECURE_AUTH_SALT',  'j.>x.H=84,lMw{ @C44@/hROh!>:u0h),i0J}/]41,V=_!P22~u7jJms C!YOlIO' );
define( 'LOGGED_IN_SALT',    'P:s5HcIk2nO@w8fhkCpem=upvO>fXVS;/~XsJZ+u4d|_VgAZ}o+ayE.k/k=o}+SS' );
define( 'NONCE_SALT',        '-C,EHv^/b5|u70k);RYg 8Pl~]D)6{?0PH5& cllvTKA?iG?7]8eby1(d@xRRRk[' );
define( 'WP_CACHE_KEY_SALT', 'uv!o%~ABG&$]_E_/T!4b#+N&JkpU-u:0{F$&V5mGKf4LqB6C|Pbn#R>R4q[po#py' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '52ce20e8a954bf35f0f4bcec34e0fd8e' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
