<?php
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
    $id = $_POST['id'];
    $newname = $_POST['newname'];
    $products = htmlspecialchars($_POST['products']);

    if (mb_strlen($newname) >= 35) {
        echo '<script>note({
              content: `<b><span class="object-alert">Редактирование чек-листа</span><br></b><b>Название рецепта не может быть длиннее 35 символов</b>`,
              type: "error",
              time: 10
            });
            </script>';
    }
    else {
        $checklist = R::findOne('checklists', 'id = ?', array($id));
        if($userid != $checklist->authorid) {
            exit ('<script>note({
              content: `<b><span class="object-alert">Редактирование чек-листа</span><br></b><b>Незивестная ошибка.</b>`,
              type: "error",
              time: 10
            });
            </script>');
        }
        $checklist->name = $newname;
        $checklist->products = $products;
        $products = str_replace("Завтрак: ", "1-", $products);
        $products = str_replace("Обед: ", "2-", $products);
        $products = str_replace("Ужин: ", "3-", $products);
        $products = str_replace("Другое: ", "4-", $products);
        $checklist->products_base = $products;
        R::store($checklist);
        exit ('<script>note({
                  content: `<b><span class="object-alert">Редактирование чек-листа</span><br></b><b>Изменения сохранены!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
        }
}

?>