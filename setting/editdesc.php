<?php
require "../db.php";
require "../mobiledetect.php";
$detect = new Mobile_Detect;

if (isset($_POST)) {
    $desc = $_POST['newdesc'];
    if (mb_strlen($desc) > 150) {
        echo '<script>note({
              content: `<b><span class="object-alert">Изменение профиля</span><br></b><b>Описание не может быть длиннее 150 символов</b>`,
              type: "error",
              time: 4
            });
            </script>';
    } else {

        if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
            if ( !empty($_COOKIE['login']) and !empty($_COOKIE['key']) ) {
                $login = $_COOKIE['login'];
                $id = $_COOKIE['id'];
                $key = $_COOKIE['key'];
                if (!($detect->isMobile() && !$detect->isAndroidOS())) {
                    $user = R::findOne('users', 'id = ? AND cookie = ?', array($id, $key));
                } else {
                    $user = R::findOne('users', 'id = ? AND cookie_mobile = ?', array($id, $key));
                }
                if (!empty($user)) {
                    session_start();
                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $user['login'];
                }
            }
        }
        $user = R::findOne('users', 'id = ?', array($_SESSION['id']));
        
        $user->description = $desc;
        R::store($user);
        echo ('<script>note({
                  content: `<b><span class="object-alert">Изменение профиля</span><br></b><b>Вы успешно сменили описание!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
    }
}
?>