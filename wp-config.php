<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'Digit-Immo' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'yayesiny' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'Oulimata123@' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'h=Q Ld2Vo44wC>j[lF83d&-;8U[eMye9jabOQd^xMy+LjPNK=BRph~i0>q{/K~1o' );
define( 'SECURE_AUTH_KEY',  'J7<i~`0)*co-X|}v,h$Z}2.6avQorODkwP_#U/~g&%+xn0]cJ<(St6<VAwM#^x!p' );
define( 'LOGGED_IN_KEY',    'n]?_o?zr3&7n&h{{JY$v,wG[ZVFfpX~42T%0j)&wA6rKy&]O+5}DSwZ5~q(ta2;!' );
define( 'NONCE_KEY',        'J~D[.sK&yO[(f$k?2_q^U*X8O=n3|j&}vc(c%.@x/cHgw<U]wWId|uQ(ii#As;n<' );
define( 'AUTH_SALT',        '<K:2ZT82JT8hbEQg6e,)gq(PK{IJYpy_$gBOkoUI(y]@Z$/C5}H5G/PAmAtn`3wL' );
define( 'SECURE_AUTH_SALT', 'y0Is;m3,fr IvzcK~G!g5!Zj81]Dn&Hte<NK^dUk}Dk_j6[{z2:V=(B#^& tz)V-' );
define( 'LOGGED_IN_SALT',   '7eH1}w1i;bt(F[7NmSb(e%j6Tig/LPr890;D/t)fi2kv8r@{yHpH=LrGS7U7>vxB' );
define( 'NONCE_SALT',       'nuqlXe^+TxLlpV/<n6j}ia-,~jh=*l2HsNQUXRWK?b<ItU!HE4j3H,e%v0u2mt~l' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
define('FS_METHOD', 'direct');
