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

$id_recipe = $_GET['r'];
$recipeobject = R::findOne('recipes', 'translated_name = ?', array($id_recipe));


$recipeauthor = R::findOne('users', 'id = ?', array($recipeobject->authorid));

$recipestypes = array(
    "0" => "Не выбрано",
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

function top($title, $description,$keywords ,$cookie) {
    include "page/top.php";
} top($recipeobject->name, "Рецепт ".$recipeobject->name." был создан ".$recipeauthor->login.". ".$recipestypes[$recipeobject->categoryid]."", "Рецепт, готовить,
 как приготовить, торт, десерт, десерты, выпечка, завтрак, обед, ужин, готовка, ужин, салат, сладко, сладенькое, ".implode(", ", explode(' ', $recipeobject->name)).", ".implode(", ", explode(' ', $recipeobject->desc))."" , $user);

if(!$recipeobject) {
    include "page/doesntexist.php";
    exit();
}

$recipeobject->views += 1;
R::store($recipeobject);

function convertToHours($steps) {
    $minutes = $steps * 15;
    $d = floor($minutes / 1440);
    $h = floor(($minutes - $d * 1440) / 60);
    $m = $minutes - ($d * 1440) - ($h * 60);
    return "{$h}ч. {$m}мин.";
}



$foodtypes_img = array(
    "мука" => "myka.png",
    "яйца куриные" => "jajtsa_kurinye.png",
    "сахар" => "sahar.png",
    "какао" => "kakao.png",
    "масло сливочное" => "maslo_slivochnoe.png",
    "творог" => "tvorog.png",
    "вода" => "voda.png",
    "масло подсолнечное" => "maslo_podsolnechnoe.png",
    "масло растительное" => "maslo_podsolnechnoe.png",
    "масло оливковое" => "maslo_olivkovoe.png",
    "сливки" => "slivki.png",
    "взбитые сливки" => "vzbitye_slivki.png",
    "шоколад белый" => "shokolad_belyj.png",
    "шоколад темный" => "shokolad_temnyj.png",
    "шоколад молочный" => "shokolad_molochnyj.png",
    "желатин" => "zhelatin.png",
    "кефир" => "kefir.png",
    "молоко" => "moloko.png",
    "соль" => "salt.png",
    "разрыхлитель" => "razrihlitel.png",
    "мёд" => "med.png",
    "мед" => "med.png",
    "твороженный сыр" => "tv_syr.png",
    "сгущенка" => "sgyshenka.png",
    "сгущёнка" => "sgyshenka.png",
    "сыр маскарпоне" => "mascarpone.png",
    "маскарпоне" => "mascarpone.png",
    "савоярди" => "savoyardi.png",
    "печенье савоярди" => "savoyardi.png",
    "сахарная пудра" => "pudra.png",
    "кофе" => "kofe.png",
    "ванилин" => "vanilin.png",
    "сода" => "soda.png",
    "желток" => "jeltok.png",
);




$recipe_data = R::findOne('userdata', 'parentid = ? AND typename = ?', [$recipeobject->id, 'recipe']);

$fav_user_data = $user->saved_data;
$fav_pagesubs = explode(',', $fav_user_data);
$fav_pagesubs_array = array_filter($fav_pagesubs, 'strlen');

//echo(array_key_exists('Мука', $foodtypes_img)) ;
$recipestepstext = array_filter(explode("-|-", $recipeobject->data), 'strlen');
$recipestepsphotoes = array_filter(explode("|||", $recipeobject->dataphotoes), 'strlen');

$recipestepsingreds = array_filter(explode("-|-", $recipeobject->ingreds), 'strlen');

?>

<!--<img src="images/foodtypes/--><?//=$foodtypes_img['Мука'];?><!--">-->
<div id="recipeBack" itemscope itemtype="http://schema.org/Recipe">
    <div id="echo_RecipeName">
            <?php if ($recipeobject->logo) : ?>
            <div class="recipeImage">
                <img itemprop="image" src="images/recipes/<?=$recipeobject->logo?>" style="width: -webkit-fill-available;">
            </div>
            <?php else : ?>
             <div class="recipeImage" style="box-shadow: none; display: flex; justify-content: center">
                <img itemprop="image" src="images/logo.png" style="width: 50%">
            </div>
            <?php endif;?>


        <div class="echo_recipeBlockDesc">
            <span class="echo_recipeType" itemprop="recipeCategory"><?=$recipestypes[$recipeobject->categoryid]?></span>
            <h1 itemprop="name" class="echo_recipeh1"><?=$recipeobject->name?></h1>
            <div class="echo_recipeAuthor">
                <a class="recipeAuthorURL" href="/user?u=<?=$recipeauthor->login?>"><?=$recipeauthor->login?></a>
                <?php if($recipeauthor->verification == 1 ):?>
                    <img src="images/verified.png" style="width: 30px; margin-left: 5%">
                <?php endif;?>
            </div>


            <span itemprop="totalTime" class="echo_recipeTime">Примерное время приготовления: <?=convertToHours($recipeobject->count_steps);?></span>
            <div class="recipeViews">
                <i class="fa fa-eye"></i>
                <?=$recipeobject->views?>
            </div>
            <?php if ($user) : ?>
                <?php if (in_array($recipe_data->id, $fav_pagesubs_array)) :?>
                    <button value="<?=$recipe_data->id?>" class="addElemToListButton_in addElemAdded" style="margin-top: 5%; margin-left: auto; margin-right: auto;">-</button>
                <?php else :?>
                    <button value="<?=$recipe_data->id?>" class="addElemToListButton_in" style="margin-top: 5%;margin-left: auto; margin-right: auto;">+</button>
                <?php endif;?>
            <?php endif;?>

        </div>

    </div>
    <div class="recipe_echo_desc">
        <?php if($recipeobject->desc): ?>
            <hr class="recipeDivider">
        <h3 class="recipe_desc_H3">Описание:</h3>
            <div itemprop="description" class="recipe_desc_text"><?=$recipeobject->desc?></div>
        <?php endif;?>
    </div>
    <hr class="recipeDivider">

    <h3 class="recipe_desc_H2">Список ингредиетов:</h3>

    <div id="recipeBlocksMain_ingreds">
        <?php for ($i = 0; $i < $recipeobject->count_ingreds; $i++) :
            $tempingredelem = explode("|d|s|", $recipestepsingreds[$i]);
            $ingredname = $tempingredelem[0];
            $ingreddose = $tempingredelem[1];
            ?>
            <?php if ($ingredname != "NONE") : ?>
                <div class="recipe_ingred_block">
                    <div class="recipe_block_img_start">
                    <?php if(array_key_exists(mb_strtolower(trim($ingredname)), $foodtypes_img)) : ?>
                        <img src="images/foodtypes/<?=$foodtypes_img[mb_strtolower(trim($ingredname))]?>" class="imgIngredEcho">
                    <?php else : ?>
                        <img src="images/logoFace.png" class="imgIngredEcho">
                    <?php endif;?>
                    </div>

                    <div class="recipe_block_desc_end">
                        <span class="recipeblockingredstyle"><?=$ingredname?></span>
                        <?php if ($ingreddose == "NONEDOZ") : ?>
                            <span itemprop="recipeIngredient" class="recipeblockingredstyle_none">Пусто</span>
                        <?php else : ?>
                            <span itemprop="recipeIngredient" class="recipeblockingredstyle_dose"><?=$ingreddose?></span>
                        <?php endif;?>

                    </div>
                </div>
            <?php endif;?>
        <?php endfor;?>
    </div>

    <h3 class="recipe_desc_H2" style="margin-top: 2%">Способ приготовления: </h3>

    <div id="recipeAlgorithmBlock" itemprop="recipeInstructions" itemtype="https://schema.org/ItemList" itemscope>
         <?php $counter = 1;
         for($i = 0; $i < $recipeobject->count_steps; $i++) :
            $text = $recipestepstext[$i];
            $photo = $recipestepsphotoes[$i];
            if ($text == "NONE" && $photo == "NOPHOTO") {continue;}
            if ($text == "NONE") {$text = "Не введено";}
             ?>
            <div class="stepDataBlock" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <div class="stepData_photo">
                    <?if ($photo == "NOPHOTO") : ?>
                        <img itemprop="image" src="images/recipes/logoFace.png" style="height: 300px; width: 300px;" class="recipeItemEchoPhoto">
                    <?php else :?>
                        <img itemprop="image" src="images/recipes/<?=$photo?>" class="recipeItemEchoPhoto">
                    <?php endif;?>
                </div>

                <div class="stepData_text">
                    <span class="Stepname">Шаг <?=$i + 1?></span>
                    <span class="StepData" itemscope><?=$text?></span>
                </div>
            </div>
        <?php endfor;?>
    </div>

    <div id="ReviewsMain">
        <?php if ($user) :  ?>
            <h3 class="reviewsh3">Оставьте свой отзыв</h3>
        <div class="reviewTextBlock">
            <textarea id="ReviewText" maxlength="1800" placeholder="Отправьте свой отзыв"></textarea>
            <button id="sendReview">Отправить отзыв</button>
        </div>
        <?php endif; ?>
        <hr class="recipeDivider">

        <div id="allReviews">
            <?php $reviews = R::findAll('reviews', 'recipeid = ?' , [$recipeobject->id]);
            foreach (array_reverse($reviews) as $review) :
                $tempuser = R::findOne('users', 'id = ?' , [$review->authorid]);
                ?>

                <div class="reviewBlock">
                    <div class="reviewTop"><a class="reviewAuthor" href="/user?u=<?=$tempuser->login?>"><?=$tempuser->login?></a>
                    <?php if ($tempuser->verification == 1) : ?>
                        <img src="images/verified.png" width="40px" height="40px">
                    <?php endif;?>
                        <span class="reviewDate"><?=$review->date?></span>
                    </div>
                    <div class="reviewBottom"><?=$review->data?></div>
                </div>

            <?php endforeach;?>
        </div>
    </div>


</div>









<div id="toTop"><img src="images/totop.png" width="100%"></div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $(window).scroll(function() {
            if($(this).scrollTop() != 0) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });
        $('#toTop').click(function() {
            $('body,html').animate({scrollTop:0},400);
        });
    });

</script>
<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')

    let elem2 = document.getElementById("nav-mainpage-mob");
    elem2.classList.remove('current')
</script>

<style>


    @media only screen and (min-device-width : 200px) and (max-device-width : 500px) {
        .addElemToListButton_in {
            height: 60px;
            width: 60px;
            font-size: 25px;
        }

    }
</style>