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

if (!$user) {
    header("Location: /");
}

$userLogin = $user->login;
function top($title, $description, $cookie) {
    include "page/top.php";
} top("Избранное", "Добро пожаловать на eleas.ru! Зарегестрируйтесь, и вы сможете добавлять свои чек-листы и рецепты, наблюдать за другими, делиться! Теперь все рецепты и меню у вас под рукой!", $user);

if(empty($user)) {
    header("Location: /signup");
}


$pagesubs_old = explode(', ', $user->subscribers);
$hissubs_old = explode(', ', $user->self_subscribe);

$pagesubs = array_filter($pagesubs_old, 'strlen');
$hissubs = array_filter($hissubs_old, 'strlen');


$useralldata_str = explode(',', $user->saved_data);
$useralldata_str_fixed = array_filter($useralldata_str, 'strlen');
//var_dump($useralldata_str_fixed);
//$useralldata = R::findAll('userdata', 'authorid = ?', [$user->id]);

$useralldata = array();
foreach ($useralldata_str_fixed as $datablock) {
    array_push($useralldata, R::findOne('userdata', 'id = ?', [$datablock]));
}

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
        <?php if ($user->admin == 0):?>
            <h1 class="userPageHeader"><?=$user->login?></h1>
        <?php elseif ($user->admin == 1 || $user->admin == 2):?>
            <h1 class="userPageHeader userPageHeader_admin"><?=$user->login?></h1>
        <?php endif;?>

        <?php if ($user->verification):?>
            <img src="images/verified.png" class="userPageVerification">
        <?php endif;?>
    </div>
    <div class="userPageDescriptionBlock"><?=$user->description?></div>
    <div id="subButtonBlock">
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
    <h2 class="userPageDataName">Избранное</h2>
</div>


<div id="pageUserAlls">
    <?php if (empty($useralldata)): ?>
        <span class="pageUserNone">Вы пока не добавили ничего!</span>
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
    let elem2 = document.getElementById("nav-mypage");
    let elem3 = document.getElementById("nav-saved");
    let elem = document.getElementById("nav-mainpage");

    elem.classList.remove('current')
    elem2.classList.remove('current')
    elem3.classList.add('current')
</script>
