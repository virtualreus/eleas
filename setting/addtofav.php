<?php
//echo $_POST['data_id'];
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
    $data_id = $_POST['data_id'];
    $user_data = $user->saved_data;
    $pagesubs = explode(',', $user_data);
    $pagesubs_array = array_filter($pagesubs, 'strlen');


    if (in_array($data_id, $pagesubs_array)) {
        unset($pagesubs_array[array_search($data_id, $pagesubs_array)]);
    } else {
        array_push($pagesubs_array, $data_id);
    }

    $newsubs = implode(',', $pagesubs_array);

    $user->saved_data = $newsubs;
    R::store($user);
    exit('<script>note({
                  content: `<b><span class="object-alert">Избранное</span><br></b><b>Успешно!</b>`,
                  type: "success",
                  time: 1
                });
                </script>');
}

?>