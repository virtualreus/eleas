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

$userLogin = $user->login;
$idList = $_GET["l"];
$checklist = R::findOne('checklists', 'id = ?', array($idList));
$checklist_products = explode(";", $checklist->products);

if($user->id != $checklist->authorid) {
    header("Location: /profile");
}


function top($title, $cookie) {
    include "page/top.php";
} top("Редактирование чек-листа", $user);

function greetEdit($title) {
    include "page/greetingEdit.php";
}
greetEdit($checklist->name);
$daysarray = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

?>
<div id="hover"></div>



<div id="id" style="display: none"><?=$checklist->id?></div>
<div id="EditListTitles">
    <input type="text" id="newListName" value="<?=$checklist->name?>" placeholder="Название:">
    <div class="mobileChecklistButtons">
        <button id="saveListMain">Сохранить</button>
        <button id="deleteListMain">Удалить</button>
        <a target="_blank" href="/checklist?l=<?=$checklist->id?>" id="viewCheckList">Просмотреть</a>
    </div>

</div>

<div id="addRecipeList">
    <h1 class="addRecipeListTitle">Выбор:</h1>

    <img src="images/close.png" width="30px" height="30px" class="close-adding_3">
    <input class="pickInput" placeholder="Поиск: ">
    <div id="informModalMain">
    </div>

</div>
<div id="informDel"></div>
<div id="informListSave"></div>
<div id="EditCheckList">
    <?$n = 1?>
    <?$d = 0?>
    <?php foreach ($checklist_products as $checklist_mini) :
        $dayproducts = explode("|", $checklist_mini);
        if (count($dayproducts) != 3) {continue;}
        ?>

        <div class="day">
            <div class="dayTitleBlock"><h1 class="dayTitle"><?=$daysarray[$d]?>:</h1></div>
            <div class="dayInputs">
                <div class="dayForms">
                    <span class="recipeIngestionEnter_mini grcolor" style="font-size: 20px; font-weight: 800">Завтрак</span>
                    <input type="text" class="dayInput" id="product<?=$n?>" value="<?=$dayproducts[0]?>" placeholder="Не выбрано" readonly>
                    <div class="checkListButtons">
                        <button class="addIngrToList product<?=$n?> 1 buttonCheckListStyles">+</button>
                        <button class="clearField product<?=$n?> buttonCheckListStyles">-</button>
                    </div>
                </div>

                <div class="dayForms">
                    <span class="recipeIngestionEnter_mini grcolor" style="font-size: 20px; font-weight: 800">Обед</span>
                    <input type="text" class="dayInput" id="product<?=$n + 1?>" value="<?=$dayproducts[1]?>" placeholder="Не выбрано" readonly>
                    <div class="checkListButtons">
                        <button class="addIngrToList product<?=$n + 1?> 2 buttonCheckListStyles">+</button>
                        <button class="clearField product<?=$n + 1?> buttonCheckListStyles">-</button>
                    </div>
                </div>

                <div class="dayForms">
                    <span class="recipeIngestionEnter_mini grcolor" style="font-size: 20px; font-weight: 800">Ужин</span>
                    <input type="text" class="dayInput" id="product<?=$n + 2?>" value="<?=$dayproducts[2]?>" placeholder="Не выбрано" readonly>
                    <div class="checkListButtons">
                        <button class="addIngrToList product<?=$n + 2?> 34 buttonCheckListStyles">+</button>
                        <button class="clearField product<?=$n + 2?> buttonCheckListStyles">-</button>
                    </div>
                </div>
            </div>
        </div>
    <?$n += 3;
      $d++;
    ?>
    <?php endforeach;?>

</div>


<style>
    @media screen and (min-device-width : 200px) and (max-device-width : 500px) {


        .recipeIngestionEnter {
            font-size: 20px !important;
        }

        .recipeIngestionEnter_mini {
            font-size: 30px !important;
        }

        .recipeNameEnter {
            font-size: 35px !important;
        }
        .pickCols img {
            width: 20%;
        }
    }


</style>

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