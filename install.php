<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

include 'lib.php';
include 'vendor/autoload.php';

$no = isset($_GET['no']) ? intval($_GET['no']) : 1;
$current_url = $_SERVER['PHP_SELF'];
if (!is_session_start()) {
    session_start();
}

if ($no == 1 && is_installed()) {
    show_error(400, "이미 설치되었습니다.<a href='" . BASE_URL . "login.php'>로그인</a>");
}

$script = array(1 => 'install_admin_id.min.js', 2 => 'keyboard_config.min.js');
$send = isset($_GET['send']) && $_GET['send'] === "1";
$result = false;
$reason = "";
$username = "";
$keyboard = "";

switch ($no)
{
	case 1:
		if(isset($_GET['send']) && $_GET['send'] === "1")
		{
			$result = user_add($_POST['username'], $_POST['password1']);
			$_SESSION['username'] = $_POST['username'];
		}

		break;
	case 2:
		if(isset($_GET['send']) && $_GET['send'] === "1")
		{
			$result = set_default_buttons(explode("\r\n", $_POST['default_buttons']));
		}

		break;
    case 3:
        $username = $_SESSION['username'];
        $keyboard = implode(", ", $DEFAULT_KEYBOARD);
        break;
    default:
        $no = 1;
}

/*
 * no $no
 * send isset($_GET['send']) && $_GET['send'] === "1"
 * current_url
 * result
 * reason
 * username
 * keyboard
 * base_url
 * script
*/
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/static');
$twig = new Twig_Environment($loader, array(
	'cache' => dirname(__FILE__) . '/resource/cache',
));

$template = $twig->load('install.twig');
echo $template->render(array(
	'no' => $no,
	'send' => isset($_GET['send']) && $_GET['send'] === "1",
	'current_url' => $current_url,
	'result' => $result,
	'reason' => $reason,
	'username' => $username,
	'keyboard' => $keyboard,
	'base_url' => BASE_URL,
	'script' => isset($script[$no]) ? $script[$no] : ""
));