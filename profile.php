<?php
require "db.php";
require "mobiledetect.php";
$detect = new Mobile_Detect;
if(!R::testConnection()) die('No DB connection!');



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


function top($title, $cookie) {
    include "page/top.php";
}

function greet($username, $user) {
    include "page/profilepage.php";
}

if ($user) {
    top($user->login, $user);
    greet($user->login, $user);
} else {
    top("Страница не найдена", $user);
//    include "page/doesntexist.php";
    header("Location: /");
}

//?>


<script>
    let elem2 = document.getElementById("nav-mypage");
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')
    elem2.classList.add('current')
</script>

<script>
    let elem3 = document.getElementById("nav-mainpage-mob");
    let elem4 = document.getElementById("nav-mypage-mob");
    elem3.classList.remove('current')
    elem4.classList.add('current')
</script>