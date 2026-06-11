<?php
/** Application version */
define('VERSION', 'v1.6.1');

/** Base URL for local development (pay attention to RewriteBase in .htaccess) */
define('BASE_URL_LOCAL', 'http://yugioh.localhost/');

/** Base URL for production (pay attention to RewriteBase in .htaccess) */
define('BASE_URL_PROD', 'https://yugioh.alddesign.at/');

/** Where to save uploaded images */
define('SAVE_IMAGE_DIR', __DIR__.'/_save/');

/** WebP quality for the imagedata() endpoint */
define('IMAGEDATA_WEBP_QUALITY', 90);

define('IS_LOCAL', $_SERVER['HTTP_HOST'] === parse_url(BASE_URL_LOCAL, PHP_URL_HOST));
define('BASE_URL', IS_LOCAL ? BASE_URL_LOCAL : BASE_URL_PROD);
define('ICON_URL', BASE_URL . 'img/_iconblack512.png');