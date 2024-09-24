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

if (isset($_POST)) {
    $name = $_POST['name'];
    $date = date("d-m-Y");

    if ($name == '') {
        exit('<script>note({
              content: `<b><span class="object-alert">Создание рецепта</span><br></b><b>Введите название</b>`,
              type: "error",
              time: 10
            });
            </script>');
    }

    elseif (mb_strlen($name) >= 30) {
    exit('<script>note({
              content: `<b><span class="object-alert">Создание рецепта</span><br></b><b>Название рецепта не может быть больше 30 символов</b>`,
              type: "error",
              time: 10
            });
            </script>');
    }

    else {
        $recipe = R::dispense('recipes');
        $recipe->name = $name;
        $recipe->author = $user->login;
        $recipe->authorid = $user->id;
        $recipe->categoryid = 0;
        $recipe->count_ingreds = 0;
        $recipe->count_steps = 0;
        $recipe->desc = "";
        $recipe->desc_photoes = "";
        $recipe->ingreds = "";
        $recipe->date = $date;
        $recipe->logo = "";
        $recipe->data = '';
        $recipe->dataphotoes = '';
        $recipe->views = 0;
        R::store($recipe);

        $todata = R::dispense("userdata");
        $todata->author = $user->login;
        $todata->authorid = $user->id;
        $todata->name = $name;
        $todata->typename = "recipe";
        $todata->typename_lat = "Рецепт";
        $todata->date = $date;
        $todata->typeletter = "r";
        $todata->parentid = $recipe->id;
        R::store($todata);


        exit ('<script>note({
                  content: `<b><span class="object-alert">Создание рецепта</span><br></b><b>Заготовка рецепта успешно создана!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
        }

}


?>