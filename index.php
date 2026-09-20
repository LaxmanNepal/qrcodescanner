<?php
/**
 * QRcdr - php QR Code generator
 * index.php
 *
 * PHP version 5.4+
 */

$version = '5.3.4';

if (version_compare(PHP_VERSION, '5.4', '<')) {
    exit("QRcdr requires at least PHP version 5.4.");
}

if (!ini_get('allow_url_fopen')) {
    exit("Please enable <code>allow_url_fopen</code>.");
}

if (!function_exists('mime_content_type')) {
    exit("Please enable the <code>fileinfo</code> extension.");
}

require __DIR__ . "/lib/functions.php";

if (qrcdr()->getConfig('debug_mode')) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', '0');
}

$relative = qrcdr()->relativePath();
require __DIR__ . '/' . $relative . 'include/head.php';

$custom_page = false;
$body_class = '';

$page = filter_input(INPUT_GET, 'p', FILTER_UNSAFE_RAW);
if (is_string($page) && preg_match('/^[a-zA-Z0-9_-]+$/', $page)) {
    $template_dir = __DIR__ . '/' . $relative . 'Template/';
    $load_page = $template_dir . $page . '.html';
    if (is_file($load_page)) {
        $custom_page = file_get_contents($load_page);
    }
}

qrcdr()->loadQRcdrCSS($version);

if (!$custom_page) {
    $body_class = 'qrcdr';
    qrcdr()->loadPluginsCss();
}

qrcdr()->setMainColor(qrcdr()->getConfig('color_primary'));
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($lang, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($rtl['dir'], ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0083fd">
    <meta name="robots" content="index,follow">
    <title><?php echo htmlspecialchars(qrcdr()->getString('title'), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(qrcdr()->getString('description'), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(qrcdr()->getString('tags'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="shortcut icon" href="<?php echo $relative; ?>images/favicon.ico">
    <link rel="stylesheet" href="<?php echo $relative; ?>bootstrap/css/bootstrap<?php echo $rtl['css']; ?>.min.css">
    <link rel="stylesheet" href="<?php echo $relative; ?>css/font-awesome.min.css">
    <script src="<?php echo $relative; ?>js/jquery-3.5.1.min.js"></script>
</head>
<body class="<?php echo htmlspecialchars($body_class, ENT_QUOTES, 'UTF-8'); ?>">
<?php
$template_dir = __DIR__ . '/' . $relative . 'Template/';
$navbar = $template_dir . 'navbar.php';
$header = $template_dir . 'header.php';
$footer = $template_dir . 'footer.php';

if (is_file($navbar)) {
    include $navbar;
}

if (is_file($header)) {
    include $header;
}

if ($custom_page) {
    echo '<main class="container mt-4">' . $custom_page . '</main>';
} else {
    include __DIR__ . '/' . $relative . 'include/generator.php';
}

if (is_file($footer)) {
    include $footer;
}

qrcdr()->loadQRcdrJS($version);

if (!$custom_page) {
    qrcdr()->loadPlugins();
}
?>
</body>
</html>
