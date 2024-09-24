<?php
require "db.php";
if(!R::testConnection()) die('No DB connection!');
$user = R::findOne('users', 'id = ?', array($_SESSION['user']->id));
$userLogin = $user->login;
$idList = $_GET["l"];

$checklist = R::findOne('checklists', 'id = ?', array($idList));
$authorobject = R::findOne('users', 'id = ?', array($checklist->authorid));
$checklist_products = explode(";", $checklist->products_base);

//function top($title, $cookie) {
//    include "page/top.php";
//} top("Меню ".$checklist->name, $user);


if (empty($checklist)) {
    header("Location: /profile.php");
}
function greetEdit($title) {
    include "page/greetingEdit.php";
}

$daysarray = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");
?>

<!DOCTYPE html>
<html>
<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/mainpage.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/alerts.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/recipes.css">
    <link rel="stylesheet" href="css/profile.css">
    <script src="/scripts/auth.js"></script>
    <script src="/scripts/alerts.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <title><?=$checklist->name?> | F.Y.</title>
    <meta charset="utf-8">
</head>

<body style="background: none">
<div class="printPage printPageTitle">
    <img src="images/logo.png" height="45%" style="padding-bottom: 1%">
    <h1 class="printPageTitleH1">Меню: <?=$checklist->name?></h1>
    <h2>Создано <?=$checklist->date?></h2>
    <h2 class="printAuthor">Автор: <?=$checklist->author?><?php if ($authorobject->verification):?><img src="images/verified.png" style="margin-left: .5%; width: 3.5%"><?php endif;?></h2>

</div>
<!--◻-->



<?php $d = 0?>
<?php foreach ($checklist_products as $checklist_day):
$dayproducts = explode("|", $checklist_day);
    if (count($dayproducts) != 3) {continue;}
?>
<div class="printPage printPageMargin">
    <h2 class="printDayName"><?=$daysarray[$d]?></h2>


    <div class="dayContent">
        <div class="printProducts">
            <?$data = explode("-",$dayproducts[0]);
            $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
            if($recipe->name != "") {
                echo "<h2>Завтрак: $recipe->name</h2>";
            } else {
                echo "<h2 style='color: gray'>Не выбрано</h2>";
            }?>
            <div class="printIngreds">
                <?php $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                <?php foreach($temp_prods as $products_day):?>
                    <?php if ($products_day != ""): ?>
                        <?="◻".$products_day."<br>"?>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
        <div class="printAlgo">
            <h2>Рецепт приготовления:</h2>
            <?php if($recipe->algorithm != "") {
                echo "<div class='printAlgoText'>$recipe->algorithm</div>";
            } else {
                echo "<h2 style='color: gray'>Пусто :(</h2>";
            }?>
        </div>
    </div>



    <div class="dayContent">
        <div class="printProducts">
            <?$data = explode("-",$dayproducts[1]);
            $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
            if($recipe->name != "") {
                echo "<h2>Завтрак: $recipe->name</h2>";
            } else {
                echo "<h2 style='color: gray'>Не выбрано</h2>";
            }?>
            <div class="printIngreds">
                <?php $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                <?php foreach($temp_prods as $products_day):?>
                    <?php if ($products_day != ""): ?>
                        <?="◻".$products_day."<br>"?>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
        <div class="printAlgo">
            <h2>Рецепт приготовления:</h2>
            <?php if($recipe->algorithm != "") {
                echo "<div class='printAlgoText'>$recipe->algorithm</div>";
            } else {
                echo "<h2 style='color: gray'>Пусто :(</h2>";
            }?>
        </div>
    </div>

    <div class="dayContent">
        <div class="printProducts">
            <?$data = explode("-",$dayproducts[2]);
            $recipe = R::findOne('minirecipes', 'authorid = ? AND name = ? AND ingestion = ?', [$authorobject->id, trim($data[1]), $data[0]]);
            if($recipe->name != "") {
                echo "<h2>Завтрак: $recipe->name</h2>";
            } else {
                echo "<h2 style='color: gray'>Не выбрано</h2>";
            }?>
            <div class="printIngreds">
                <?php $temp_prods = explode("<br>",$recipe->ingreds_base);?>
                <?php foreach($temp_prods as $products_day):?>
                    <?php if ($products_day != ""): ?>
                        <?="◻".$products_day."<br>"?>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
        <div class="printAlgo">
            <h2>Рецепт приготовления:</h2>
            <?php if($recipe->algorithm != "") {
                echo "<div class='printAlgoText'>$recipe->algorithm</div>";
            } else {
                echo "<h2 style='color: gray'>Пусто :(</h2>";
            }?>
        </div>
    </div>

    <?php $d++;?>
</div>
<?php endforeach;?>
<!--<div class="printPage"></div>-->
<!--<div class="printPage"></div>-->
<!--<div class="printPage"></div>-->
<!--<div class="printPage"></div>-->
<!--<div class="printPage"></div>-->
</body>
</div>
</html>

<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')
</script>