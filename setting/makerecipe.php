<?php
require "../db.php";
$mysqli = new mysqli("localhost", "nt_3008", "cJ5rP8uT", "project");
require "../mobiledetect.php";
$detect = new Mobile_Detect;

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
$userid = $user->id;

if (isset($_POST)) {
    $name = $_POST['name'];
    $ingestion = $_POST['ingestion'];
    $ingestion_text = $_POST['ingestion_text'];


    if (empty($name)) {
        echo '<script>note({
              content: `<b><span class="object-alert">Создание мини-рецепта</span><br></b><b>Введите название.</b>`,
              type: "error",
              time: 10
            });
            </script>';
    }
    elseif (mb_strlen($name) >= 35) {
        echo '<script>note({
              content: `<b><span class="object-alert">Создание мини-рецепта</span><br></b><b>Название рецепта не может быть длиннее 35 символов</b>`,
              type: "error",
              time: 10
            });
            </script>';
    }

    else {
        $minirecipe = R::dispense('minirecipes');
        $minirecipe->name = htmlspecialchars(trim($name));
        $minirecipe->author = $user->login;
        $minirecipe->authorid = $user->id;
        $minirecipe->count = 1;
        $minirecipe->ingestion = $ingestion;
        $minirecipe->ingestion_text = $ingestion_text;
        $minirecipe->ingreds_base = "";
        $minirecipe->algorithm = "";

        $checkvalidity = R::findAll('minirecipes', 'authorid = ? AND ingestion = ? AND name = ?', [$_SESSION['user']->id, $ingestion, $name]);

        if (!empty($checkvalidity)) {
            exit ('<script>note({
                  content: `<b><span class="object-alert">Создание мини-рецепта</span><br></b><b>' . $ingestion_text . ' с названием ' . $name . ' уже существует!</b>`,
                  type: "error",
                  time: 10
                });
               
                </script>');
        } else {
            R::store($minirecipe);
            
            exit ('<script>note({
                  content: `<b><span class="object-alert">Создание мини-рецепта</span><br></b><b>Заготовка минирецепта создана!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
        }
    }

}

?>