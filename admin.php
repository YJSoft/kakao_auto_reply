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

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== TRUE) {
    show_error(403, "Please login. <a href=\"" . BASE_URL . "login.php\">Login</a>");
}

$action = isset($_GET['action']) ? $_GET['action'] : 'default';

$title = array('default' => '관리자 페이지', 'add' => '버튼 추가하기', 'keyboard' => 'Home Keyboard 보기 / 수정', 'find' => '파일명 찾기', 'test' => '테스트 하기', 'logout' => '로그아웃');
$script_lists = array('add' => 'add_button.min.js', 'keyboard' => 'keyboard_update.min.js', 'test' => 'kakao_test.min.js');
$result = "";

switch($action) {
    case "add":
        if(isset($_GET['post']) && $_GET['post'] === "1") $result = add_button();
        break;
    case "keyboard":
        if(isset($_GET['post']) && $_GET['post'] === "1") {
            $result = set_default_buttons(explode("\r\n", $_POST['default_buttons']));
        }
        break;
    case "find":
        if(isset($_POST['button_name'])) {
			$filename = get_message_filename($_POST['button_name']);
			$filename_with_path = get_message_file($_POST['button_name']);
			$result =file_exists($filename_with_path);
        }
        break;
    case "logout":
        unset($_SESSION['is_admin']);
        header("Location: ".BASE_URL."login.php");
        break;
}
if(isset($script_lists[$action])) {
    $script = $script_lists[$action];
} else {
	$script = "";
}

/*
 * title $title[$action]
 * php_self $_SERVER['PHP_SELF']
 * action $action
 * post isset($_GET['post']) && $_GET['post'] === "1"
 * result $result
 * base_url BASE_URL
 * script $script
 */
$loader = new Twig_Loader_Filesystem('/static');
$twig = new Twig_Environment($loader, array(
	'cache' => '/resource/cache',
));

$template = $twig->load('admin.twig');
echo $template->render(array(
	'title' => $title[$action],
	'php_self' => $_SERVER['PHP_SELF'],
	'action' => $action,
	'post' => isset($_GET['post']) && $_GET['post'] === "1",
	'result' => $result,
	'base_url' => BASE_URL,
	'script' => $script,
));