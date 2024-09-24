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
$myuserid = $user->id;

if (isset($_POST)) {
    $user_name = $_POST['userid'];
    $pageuser = R::findOne('users', 'login = ?', array($user_name));
    $pagesubs = explode(', ', $pageuser->subscribers);

    $mysubs = explode(', ', $user->self_subscribe);

    if (in_array($myuserid, $pagesubs)) {

        unset($pagesubs[array_search($myuserid,$pagesubs)]);
        unset($mysubs[array_search($pageuser->id,$mysubs)]);

        $newsubs = implode(', ', array_filter($pagesubs, 'strlen'));
        $newmysubs = implode(', ', array_filter($mysubs, 'strlen'));

        $pageuser->subscribers = $newsubs;
        $user->self_subscribe = $newmysubs;
        R::store($pageuser);
        R::store($user);
        echo '<button class="subButtonButton nonsubbed">Подписаться</button>';
        echo ('<script>note({
                  content: `<b><span class="object-alert">'.$pageuser->login.'</span><br></b><b>Вы успешно отписались!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');

    } else {
        array_push($pagesubs, $myuserid);
        array_push($mysubs, $pageuser->id);

        $newsubs = implode(', ', array_filter($pagesubs, 'strlen'));
        $newmysubs = implode(', ', array_filter($mysubs, 'strlen'));

        $pageuser->subscribers = $newsubs;
        $user->self_subscribe = $newmysubs;
        R::store($pageuser);
        R::store($user);
        echo '<button class="subButtonButton subbed">отменить подписку</button>';
        echo ('<script>note({
                  content: `<b><span class="object-alert">'.$pageuser->login.'</span><br></b><b>Вы успешно подписались!</b>`,
                  type: "success",
                  time: 10
                });
                setTimeout(() => { window.location.reload(); }, 500);
                </script>');
    }
}

?>