<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */
include_once "lib.php";
include 'vendor/autoload.php';

if (!is_installed()) {
    exit('Need to install! <a href="' . BASE_URL . 'install.php">Install</a>');
}

if (!is_session_start()) {
    session_start();
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === TRUE) {
    echo '<script>location.href="' . BASE_URL . 'admin.php"</script>';
}
$is_ok = isset($_GET['login']) ? intval($_GET['login']) : 0;
$login = false;
if($is_ok == 1 && check_login($_POST['username'], $_POST['password']))
{
	$_SESSION['is_admin'] = TRUE;
	$login = true;
}

$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/static');
$twig = new Twig_Environment($loader, array(
	'cache' => dirname(__FILE__) . '/resource/cache',
));

$template = $twig->load('login.twig');
echo $template->render(array(
	'is_ok' => $is_ok,
	'php_self' => $_SERVER['PHP_SELF'],
	'login' => $login,
	'base_url' => BASE_URL
));