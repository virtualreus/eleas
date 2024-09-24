<?php
require "db.php";
if(!R::testConnection()) die('No DB connection!');
require "mobiledetect.php";

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


$pageuser = $_GET["u"];
$userobject = R::findOne('users', 'login = ?', array($pageuser));
$useralldata = R::findAll('userdata', 'authorid = ?', [$userobject->id]);

function top($title, $cookie) {
    include "page/top.php";
}
//if ($user->id == $userobject->id) {
//    header("Location: /profile.php");
//}
if (!$userobject) {
    top("Пользователь не найден", $user);
            include "page/doesntexist.php";
            exit();
}
top("Профиль ".$userobject->login, $user);

$pagesubs_old = explode(', ', $userobject->subscribers);
$hissubs_old = explode(', ', $userobject->self_subscribe);

$pagesubs = array_filter($pagesubs_old, 'strlen');
$hissubs = array_filter($hissubs_old, 'strlen');

$recipestypes = array(
    "0" => "-не выбрано-",
    "1" => "Выпечка и десерты",
    "2" => "Основное блюдо",
    "3" => "Завтрак",
    "4" => "Обед",
    "5" => "Ужин",
    "6" => "Салат",
    "7" => "Морепродукты",
    "8" => "Сладкое",
    "9" => "Супы"
);
?>


<div class="userPageTitle">
    <div class="userPageTitleText">
        <?php if ($userobject->admin == 0):?>
            <h1 class="userPageHeader"><?=$userobject->login?></h1>
        <?php elseif ($userobject->admin == 1):?>
            <h1 class="userPageHeader userPageHeader_admin"><?=$userobject->login?></h1>
        <?php endif;?>

            <?php if ($userobject->verification):?>
                <img src="images/verified.png" class="userPageVerification">
            <?php endif;?>
    </div>
        <div class="userPageDescriptionBlock"><?=$userobject->description?></div>
    <div id="subButtonBlock">

        <?php
        if ($user) {
            if ($user->id != $userobject->id) {
                if (in_array($user->id, $pagesubs)) {
                    echo '<button class="subButtonButton subbed">отменить подписку</button>';
                } else {
                    echo '<button class="subButtonButton nonsubbed">Подписаться</button>';
                }
            }
        }
        ?>

    </div>

    <div class="subsBlock">
        <div class="subsElem">
            <span class="subsName">Подписчики:</span>
            <span class="subsCount"><?=count($pagesubs)?></span>
        </div>
        <div class="subsElem">
            <span class="subsName">Подписки:</span>
            <span class="subsCount"><?=count($hissubs)?></span>
        </div>
    </div>

</div>

<div id="userPageData">
    <h2 class="userPageDataName">Загрузки пользователя <?=$userobject->login?></h2>
</div>

<div id="pageUserAlls">
        <?php if (empty($useralldata)): ?>
            <span class="pageUserNone"><?=$userobject->login?> пока не добавил ничего!</span>
        <?php endif;?>
        <?php foreach (array_reverse($useralldata) as $userobject) :
            if ($userobject->typename == "checklist") : ?>
        <a href="/<?=$userobject->typename?>?<?=$userobject->typeletter?>=<?=$userobject->parentid?>" target="_blank" class="userDetailBlock">
            <img src="/images/logoFace.png" class="userDetailFaceIMG">
            <span class="userDetailDesc"><?=$userobject->typename_lat?></span>
            <span class="userDetailName"><?=$userobject->name?></span>
            <span class="userDetailDate"><?=$userobject->date?></span>
            <span class="userDetailDate"><?=$userobject->author?></span>
        </a>
            <?php elseif ($userobject->typename == "recipe") :
                $tempelem = R::findOne('recipes', 'id = ?', [$userobject->parentid]);
                ?>
                <a href="/recipe?r=<?=$tempelem->translated_name?>" target="_blank" style="background-image: url(images/recipes/<?=$tempelem->logo?>);" class="recipeBlock_profile_saved">
                    <div class="recipesList_Details">
                        <span class="recipesList_desc"><?=$recipestypes[$tempelem->categoryid]?></span>
                        <span class="recipesList_name"><?=$tempelem->name?></span>
                        <span class="recipesList_date"><?=$tempelem->date?></span>
                    </div>
                </a>

            <?php endif;?>
        <?php endforeach;?>
</div>
<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')
</script>

<style>
    .nonsubbed {
        background-image: linear-gradient(to right, #87f0d2 0%, #1fc295 51%, #87f0d2 100%);
        color: white;
    }

    .subbed {
        /*background: #efefef;*/
        background-image: linear-gradient(to right, #efefef 0%, #e6e6e6 51%, #efefef 100%);
        color: #1fc295;
    }

</style>