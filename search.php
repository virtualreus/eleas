<?php
require "db.php";
if(!R::testConnection()) die('No DB connection!');

require "mobiledetect.php";
$detect = new Mobile_Detect;

$querry = urldecode($_GET['q']);

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
function top($title, $description, $cookie) {
    include "page/top.php";
} top("Поиск:", "Добро пожаловать на eleas.ru! Зарегестрируйтесь, и вы сможете добавлять свои чек-листы и рецепты, наблюдать за другими, делиться! Теперь все рецепты и меню у вас под рукой!", $user);

$findeddata = R::findAll('userdata', 'name LIKE ?', ["%$querry%"]);

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
    <h1 class="searchDesc">Поиск: <?=$querry?></h1>
    <span class="findedCols">Показано результатов: <?=count($findeddata)?></span>
</div>


<?php foreach ($findeddata as $datablock) :
    if ($datablock->typename == "checklist") : ?>
    <a href="<?=$datablock->typename?>?<?=$datablock->typeletter?>=<?=$datablock->parentid?>" target="_blank" class="findedBlock">
        <img src="images/logoFace.png" width="100px" height="100px" style="margin-left: 2%">
        <span class="userDetailDesc"><?=$datablock->typename_lat?></span>
        <span class="userDetailNameFind"><?=$datablock->name?></span>
        <span class="userDetailDesc">Автор: <?=$datablock->author?></span>
    </a>
    <?php elseif ($datablock->typename == "recipe") :
        $tempelem = R::findOne('recipes', 'id = ?', [$datablock->parentid]);
        ?>
        <a href="/recipe?r=<?=$tempelem->id?>" target="_blank" style="background-image: url(images/recipes/<?=$tempelem->logo?>); padding-bottom: 10px" class="recipeBlock_profile_onpage">
            <div class="recipesList_Details">
                <span class="recipesList_desc"><?=$recipestypes[$tempelem->categoryid]?></span>
                <span class="recipesList_name"><?=$tempelem->name?></span>
                <span class="recipesList_date"><?=$tempelem->author?></span>
                <span class="recipesList_date"><?=$tempelem->date?></span>
            </div>

        </a>
    <?php endif;?>
<?php endforeach;?>



<style>
    .userPageTitle {
        display: flex;
        flex-direction: column;
        align-items: baseline;
    }

    .userDetailDesc {
        margin-left: 4%;
        margin-top: 0;
    }
</style>
