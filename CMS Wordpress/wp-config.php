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
define( 'DB_NAME', 'u627170956_sjDzE' );

/** Database username */
define( 'DB_USER', 'u627170956_6ACMs' );

/** Database password */
define( 'DB_PASSWORD', '4rZ5G7uv8C' );

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
define( 'AUTH_KEY',          'f`-WxAo1ujcu>6*iMT87/p`<V[T#__aE7R&pKT09qU%V5U3o`vM?C2?5?5Fkga^f' );
define( 'SECURE_AUTH_KEY',   'ro:pHS~q<-FQt1`kTYj;pI+4[=I.9c^BDea/jpPR_mM*,ccGt/ub/;I$zFw22$Ma' );
define( 'LOGGED_IN_KEY',     ')Z$4weT7#G-8r=4e RfYUQr7y6JEd2>Q}}}iEr:sujr}{Q(zr{_AP!>0 }?UO++}' );
define( 'NONCE_KEY',         'cUmEQ,uAGY4rXriy5jE^AYHb.{$TH&0;K3QgdRa;}DVcyBz|W|c[v8y=ll:hdxz*' );
define( 'AUTH_SALT',         'apdkHkiHR5R.=[ S<Ic*Bd8>;.T9)CK9el,J1H[w`NnZ^NS&Jd<he]Ajqg:sshA+' );
define( 'SECURE_AUTH_SALT',  'OS4vJgNrb_) L6LfKm[+]~=IfB?7 <Y,RHQ[cpck3^8Wv>8^Ic}4{}]`LOXbW83-' );
define( 'LOGGED_IN_SALT',    '`,kiG,mcE@s ~S[vrR6j$zbT>N>`B]34b[`:hkXM$<bCVq/7C#eJ],z~CU^}G+7@' );
define( 'NONCE_SALT',        'Ic$@wb@GwXq+vHF?O4;f1Cw-48i}bxs(F1[pl9iJB^y=Y77B+}R:[V[pR~=KOAd ' );
define( 'WP_CACHE_KEY_SALT', 'cty]~AnH3Obk]cymKrh~l*O(!o:PE:G/q*;OR(IDXbJ-aaovE`1<yX3NRk!^okAt' );


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
define( 'COOKIEHASH', '70260960bba3d2dfb9de3d9974c84886' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
