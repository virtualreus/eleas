<?php
date_default_timezone_set('UTC');
require "../db.php";
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
    if (empty($name)) {
        echo '<script>note({
              content: `<b><span class="object-alert">Создание чек-листа</span><br></b><b>Введите название.</b>`,
              type: "error",
              time: 10
            });
            </script>';
    }

    else if (mb_strlen($name) >= 35) {
        echo '<script>note({
              content: `<b><span class="object-alert">Создание чек-листа</span><br></b><b>Название рецепта не может быть длиннее 35 символов</b>`,
              type: "error",
              time: 10
            });
            </script>';
    } else {
        $checklist = R::dispense('checklists');
        $checklist->name = $name;
        $checklist->author = $user->login;
        $checklist->authorid = $user->id;
        $checklist->products = "||;||;||;||;||;||;||;";
        $checklist->products_base = "||;||;||;||;||;||;||;";
        $checklist->date = date("d-m-Y");
        R::store($checklist);

        $datachecklist = R::dispense('userdata');
        $datachecklist->author = $user->login;
        $datachecklist->authorid = $user->id;
        $datachecklist->parentid = $checklist->id;
        $datachecklist->name = $name;
        $datachecklist->typename = 'checklist';
        $datachecklist->typename_lat = "Чек-лист";
        $datachecklist->typeletter = "l";
        $datachecklist->date = date("d-m-Y");
        R::store($datachecklist);


        exit ('<script>note({
                  content: `<b><span class="object-alert">Создание чек-листа</span><br></b><b>Заготовка чек-листа успешно создана!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
    }

}
?>