<?php
require "../db.php";
require "../mobiledetect.php";
$detect = new Mobile_Detect;

if (isset($_POST)) {
    $newname = $_POST['newname'];
    $id = $_POST['id'];
    $recipe = R::findOne('minirecipes', 'id = ?', array($id));
    $ingreds = $_POST['ingreds'];
    $algo = $_POST['algo'];
    $ingestion = $_POST['ingestion'];
    $ingestion_text = $_POST['ingestion_text'];
    $recipe->ingreds_base = $ingreds;
    $recipe->algorithm = $algo;
    $recipe->ingestion = $ingestion;
    $recipe->ingestion_text = $ingestion_text;
    R::store($recipe);


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


    if (mb_strlen($newname) > 35) {
        echo '<script>note({
              content: `<b><span class="object-alert">Редактирование рецепта</span><br></b><b>Название рецепта не может быть длиннее 35 символов</b>`,
              type: "error",
              time: 10
            });
            </script>';
    } else if (mb_strlen($algo) > 800) {
        echo '<script>note({
              content: `<b><span class="object-alert">Редактирование рецепта</span><br></b><b>Описание рецепта не может быть длиннее 800 символов</b>`,
              type: "error",
              time: 10
            });
            </script>';
    }
    elseif (!empty($newname)) {



        $checkvalidity = R::findAll('minirecipes', 'authorid = ? AND ingestion = ? AND name = ?', [$user->id, $ingestion, $newname]);
        if (!empty($checkvalidity)  && $newname != $recipe->name) {
            exit ('<script>note({
                  content: `<b><span class="object-alert">Создание мини-рецепта</span><br></b><b>' . $recipe->ingestion_text . ' с названием ' . $newname . ' уже существует!</b>`,
                  type: "error",
                  time: 10
                });
               
                </script>'); }
         else {
             $recipe->name = $newname;
             R::store($recipe);
             exit ('<script>note({
                  content: `<b><span class="object-alert">Редактирование рецепта</span><br></b><b>Изменения успешно сохранены!</b>`,
                  type: "success",
                  time: 10
                });
                 setTimeout(() => { window.location.reload(); }, 500);
                </script>');

         }
    }
}
?>