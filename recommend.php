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

$userLogin = $user->login;
function top($title, $description, $keywords, $cookie) {
    include "page/top.php";
} top("Рекомендованное", "Добро пожаловать на eleas.ru! Зарегестрируйтесь, и вы сможете добавлять свои чек-листы и рецепты, наблюдать за другими, делиться! Теперь все рецепты и меню у вас под рукой!",
    "Рецепт, готовить, как приготовить, торт, десерт, десерты, выпечка, завтрак, обед, ужин, готовка, ужин, салат, сладко, сладенькое", $user);



$subbedarray = R::findAll('users');
usort($subbedarray, function ($a, $b) {
return $b['subscribers'] <=> $a['subscribers'];
});

$mysubs = array();

foreach ($subbedarray as $subelems) {
array_push($mysubs, $subelems->id);
}
$subdata = array();


//foreach ($mysubs as $mysubElem) {
//$loop_elem = R::findAll('userdata', 'authorid = ?', [$mysubElem]);
//    foreach ($loop_elem as $loop_elem_mini) {
//        array_push($subdata, $loop_elem_mini);
//    }
//}
$recfind = (int)$_GET['find'];
if ($recfind) {
    if ($recfind == 9) {
        foreach ($mysubs as $mysubElem) {
            $loop_elem = R::findAll('userdata', 'authorid = ?', [$mysubElem]);
            foreach ($loop_elem as $loop_elem_mini) {
                if ($loop_elem_mini->typename == "checklist") {
                    array_push($subdata, $loop_elem_mini);
                }
            }
        }
    }
    else {
        foreach ($mysubs as $mysubElem) {
            $loop_elem = R::findAll('userdata', 'authorid = ?', [$mysubElem]);
            foreach ($loop_elem as $loop_elem_mini) {
                if ($loop_elem_mini->typename == "recipe") {
                    $tempelems = R::findOne('recipes', "id = ?", [$loop_elem_mini->parentid]);
                    if ($tempelems->categoryid == $recfind) {
                        array_push($subdata, $loop_elem_mini);
                    }
                }
            }
        }
    }
}
else {
    foreach ($mysubs as $mysubElem) {
        $loop_elem = R::findAll('userdata', 'authorid = ?', [$mysubElem]);
        foreach ($loop_elem as $loop_elem_mini) {
            array_push($subdata, $loop_elem_mini);
        }
    }
}

usort($subdata, function ($a, $b) {
    return $b['id'] <=> $a['id'];
});


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


<div id="welcomePage">
    <div id="leftBarMain">
        <h2 class="leftBarSubsTitle">Пользователи:</h2>

        <?php foreach ($subbedarray as $sub_id):
            $pagesubs_old = explode(', ', $sub_id->subscribers);
            $pagesubs = array_filter($pagesubs_old, 'strlen');
            ?>
            <div class="leftBarSub">
                <img src="/images/logoFace.png" width="50px" height="50px">
                <div class="subUserBlockData">
                    <a href="/user?u=<?=$sub_id->login?>" target="_blank" class="leftBarLink">
                        <?=$sub_id->login?><?php if($sub_id->verification) :?><img src="/images/verified.png" style="margin-left: 4%;" width="20px" height="20px"><?php endif;?>
                    </a>
                    <span class="leftBarCountSubs"><?=count($pagesubs)?> подписч.</span>
                </div>
            </div>
        <?php endforeach;?>
    </div>

    <div id="main-wrapper">
        <div id="main_PageData">
            <h2 class="main_PageDataHeader">Популярное</h2>
        </div>
        <div id="main_Links">
            <a href="?find=1" class="getButton">Выпечка и десерты</a>
            <a href="?find=2" class="getButton">Основное блюдо</a>
            <a href="?find=3" class="getButton">Завтрак</a>
            <a href="?find=4" class="getButton">Обед</a>
            <a href="?find=5" class="getButton">Ужин</a>
            <a href="?find=6" class="getButton">Морепродукты</a>
            <a href="?find=7" class="getButton">Сладкое</a>
            <a href="?find=8" class="getButton">Супы</a>
            <a href="?find=9" class="getButton">Чек-листы</a>
        </div>
        <div id="addToFav"></div>

        <?php foreach ($subdata as $subitem) :
            $loop_user_item = R::findOne('users', 'id = ?', [$subitem->authorid]);
            if($subitem->typename == "checklist") :?>
            <div class="main_eleM">
                <a href="/user?u=<?=$loop_user_item->login?> " target="_blank" class="main_elem_title">
                    <img src="/images/logoFace.png" width="45px" height="45px">
                    <span class="main_elem_name"><?=$loop_user_item->login?></span>
                    <?php if($loop_user_item->verification == 1) :?>
                        <img src="/images/verified.png" style="margin-left: 1%" width="20px" height="20px">
                    <?php endif;?>
                    <span class="main_date"><?=$subitem->date?></span>
                </a>

                <div class="main_eleM_data">
                    <img src="/images/logo.png" width="140px" height="140px">
                    <span class="mainItem_surname"><?=$subitem->typename_lat?></span>
                    <a href="/<?=$subitem->typename?>?<?=$subitem->typeletter?>=<?=$subitem->parentid?>" class="mainItem_name"><?=$subitem->name?></a>
                </div>
            </div>

            <?php elseif($subitem->typename == "recipe") :
               $tempelem = R::findOne('recipes', 'id = ?', [$subitem->parentid]);
               ?>

            <a href="/recipe?r=<?=$tempelem->translated_name?>" target="_blank" style="background-image: url(images/recipes/<?=$tempelem->logo?>);" class="recipeBlock_profile_onpage">
                <div class="recipesList_Details">
                    <span class="recipesList_desc"><?=$recipestypes[$tempelem->categoryid]?></span>
                    <span class="recipesList_name"><?=$tempelem->name?></span>
                    <span class="recipesList_date"><?=$tempelem->author?></span>
                    <span class="recipesList_date"><?=$tempelem->date?></span>
                </div>
            </a>
            <?php endif;?>
        <?php endforeach;?>
    </div>
</div>


<script>
    let elem = document.getElementById("nav-mainpage");
    let elem2 = document.getElementById("nav-reco");
    elem.classList.remove('current')
    elem2.classList.add('current')
</script>


<script>
    let elem = document.getElementById("nav-mainpage-mob");
    let elem2 = document.getElementById("nav-reco-mob");
    elem.classList.remove('current')
    elem2.classList.add('current')
</script>