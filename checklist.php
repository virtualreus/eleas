<?php
require "db.php";
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
$idList = $_GET["l"];

$checklist = R::findOne('checklists', 'id = ?', array($idList));

$checklist_products = explode(";", $checklist->products_base);



if(!R::testConnection()) die('No DB connection!');

$detect = new Mobile_Detect;



function top($title, $description, $cookie) {
    include "page/top.php";
} top("Меню ".$checklist->name, "Меню \"$checklist->name\" было создано $checklist->date пользователем $checklist->author! Приятного аппетита!" ,$user);


if (empty($checklist)) {
    include "page/doesntexist.php";
    exit();
}
function greetEdit($title) {
    include "page/greetingEdit.php";
}


$daysarray = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");
$authorobject = R::findOne('users', 'id = ?', array($checklist->authorid));

$fav_user_data = $user->saved_data;
$fav_pagesubs = explode(',', $fav_user_data);
$fav_pagesubs_array = array_filter($fav_pagesubs, 'strlen');


$checklist_data = R::findOne('userdata', 'parentid = ? AND typename = ?', [$checklist->id, 'checklist']);

?>


<div id="hover"></div>
<div id="ModalPrint">
    <img src="images/close.png" width="30px" height="30px" class="close-adding-5">
    <h2>Печать чек-листа</h2>
    <span>Сейчас откроется удобная для печати страница.</span>
    <span>Вам нужно:</span>
    <ol class="needOl">
        <li class="needLi">Нажать на открывшейся странице в любом месте правой кнопкой мыши и выбрать "Печать".</li>
        <li class="needLi">Выбрать альбомную ориентацию.</li>
        <li class="needLi">Выбрать "Поля" - "Нет".</li>
        <li class="needLi">Дополнительные настройки (больше параметров) -> Параметры (Настройки) -> Отметить галочкой "фон".</li>
    </ol>
    <a class="printThisList" style="text-align: center;" href="printchecklist.php?l=<?=$idList?>" target="_blank">Открыть</a>
</div>


<div id="addToFav_in"></div>
<div id="GreetEdit">
    <h1 class="Editname">Меню: <?=$checklist->name?> </h1><a target="_blank" href="/user?u=<?=$checklist->author?>" class="minidesc">Автор: <?=$checklist->author?></a>
    <?php if ($authorobject->verification):?>
    <img src="images/verified.png" style="margin-left: .5%; height: 1.5%; width: 1.5%">
    <?php endif;?>
	        <a class="printThisList printThisListModal">Печать</a>


<!--    <button value="--><?//=$checklist->id?><!--" class="addElemToListButton_in addElemAdded">-</button>-->
    <?php if ($user) : ?>
        <?php if (in_array($checklist_data->id, $fav_pagesubs_array)) :?>
            <button value="<?=$checklist_data->id?>" class="addElemToListButton_in addElemAdded">-</button>
        <?php else :?>
            <button value="<?=$checklist_data->id?>" class="addElemToListButton_in">+</button>
        <?php endif;?>
    <?php endif;?>

</div>


<?php $d = 0?>
<?php foreach ($checklist_products as $checklist_day):
    $dayproducts = explode("|", $checklist_day);
?>

<div class="echo_Day">
    <h1 class="echo_DayTitle grcolor"><?=$daysarray[$d]?></h1>

    <div class="echo_DayFeed">
        <?php
        $data = explode("-",$dayproducts[0]);
        $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
        if($recipe->name != "") {
            echo "<h2>Завтрак: $recipe->name</h2>";
        } else {
            echo "<h2 style='color: gray'>Не выбрано</h2>";
        }?>

        <div class="echo_Content">
            <div class="echo_Ingreds">
                <?php
                $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                    <?php foreach($temp_prods as $products_day):?>
                        <?=$products_day."<br>"?>
                    <?php endforeach;?>
            </div>
            <div class="echo_Algo">
                <h2 class="">Рецепт приготовления:</h2>
                <?php if($recipe->algorithm != "") {
                    echo "$recipe->algorithm";
                } else {
                    echo "<h2 style='color: gray'>Пусто :(</h2>";
                }?>
            </div>
        </div>
    </div>



    <div class="echo_DayFeed">
        <?php
        $data = explode("-",$dayproducts[1]);
        $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
        if($recipe->name != "") {
            echo "<h2>Обед: $recipe->name</h2>";
        } else {
            echo "<h2 style='color: gray'>Не выбрано</h2>";
        }?>

        <div class="echo_Content">
            <div class="echo_Ingreds">
                <?php
                $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                <?php foreach($temp_prods as $products_day):?>
                    <?=$products_day."<br>"?>
                <?php endforeach;?>
            </div>
            <div class="echo_Algo">
                <h2 class="">Рецепт приготовления:</h2>
                <?php if($recipe->algorithm != "") {
                    echo "$recipe->algorithm";
                } else {
                    echo "<h2 style='color: gray'>Пусто :(</h2>";
                }?>
            </div>
        </div>
    </div>

    <div class="echo_DayFeed">
        <?php
        $data = explode("-",$dayproducts[2]);
        $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
        if($recipe->name != "") {
            echo "<h2>Ужин: $recipe->name</h2>";
        } else {
            echo "<h2 style='color: gray'>Не выбрано</h2>";
        }?>

        <div class="echo_Content">
            <div class="echo_Ingreds">
                <?php
                $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                <?php foreach($temp_prods as $products_day):?>
                    <?=$products_day."<br>"?>
                <?php endforeach;?>
            </div>
            <div class="echo_Algo">
                <h2 class="">Рецепт приготовления:</h2>
                <?php if($recipe->algorithm != "") {
                    echo "$recipe->algorithm";
                } else {
                    echo "<h2 style='color: gray'>Пусто :(</h2>";
                }?>
            </div>
        </div>
    </div>

    <?php $d++;?>
</div>
<?php endforeach; ?>



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
            $('body,html').animate({scrollTop:0},600);
        });
    });

</script>


<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')
</script>