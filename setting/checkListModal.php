<?php

require "../db.php";
if(!R::testConnection()) die('No DB connection!');

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

$userid = $user->id;

if (isset($_POST)) {
    $belongs = $_POST['belongs'];
    if ((int)$belongs == 1) {
        $recipes = R::findAll('minirecipes', 'authorid = ? AND ingestion = ?', [$userid, "1"]);

        echo ('<div id="informModal">');
        foreach (array_reverse($recipes) as $recipeelem) {

            echo ('
                <hr class="recipeLine">
    <div class="pickRecipe">
        <div class="pickCols">

           <img src="images/logo.png" class="" width="15%">
            <div class="pickInfo">
                <a href="/viewminirecipe?r='.$recipeelem->id.'" class="recipeNameEnter" target="_blank">'.$recipeelem->name.'</a>
                <span class="recipeIngestionEnter">'.$recipeelem->ingestion_text.'</span>
                <span class="recipeIngestionEnter" style="color: gray; font-size: 13px;">Кол-во продуктов: '.$recipeelem->count.'</span>
            </div>
           <img src="images/continue.png" class="setThisToList" width="15%" height="15%" alt="'.$recipeelem->ingestion_text.': '.$recipeelem->name.'">
        </div>
    </div>
                <hr class="recipeLine">
    ');
        }echo ('</div>');
    }

    elseif ((int)$belongs == 2) {



        $recipes = R::findAll('minirecipes', 'authorid = ? AND ingestion = ?', [$userid, "2"]);

        echo ('<div id="informModal">');
        foreach (array_reverse($recipes) as $recipeelem) {

            echo ('
                <hr class="recipeLine">
    <div class="pickRecipe">
        <div class="pickCols">

           <img src="images/logo.png" class="" width="15%">
            <div class="pickInfo">
                <a href="/viewminirecipe?r='.$recipeelem->id.'" class="recipeNameEnter" target="_blank">'.$recipeelem->name.'</a>
                <span class="recipeIngestionEnter">'.$recipeelem->ingestion_text.'</span>
                <span class="recipeIngestionEnter" style="color: gray; font-size: 13px;">Кол-во продуктов: '.$recipeelem->count.'</span>
            </div>
           <img src="images/continue.png" class="setThisToList" width="15%" height="15%" alt="'.$recipeelem->ingestion_text.': '.$recipeelem->name.'">
        </div>
    </div>
                <hr class="recipeLine">
    ');
        }echo ('</div>');

    }

    elseif ((int)$belongs == 34) {
        $recipes = R::findAll('minirecipes', 'authorid = ? AND (ingestion = ? OR ingestion = ?)', [$userid, "3", "4"]);
        echo ('<div id="informModal">');
        foreach (array_reverse($recipes) as $recipeelem) {

            echo ('
                <hr class="recipeLine">
    <div class="pickRecipe">
        <div class="pickCols">

           <img src="images/logo.png" class="" width="15%">
            <div class="pickInfo">
                <a href="/viewminirecipe?r='.$recipeelem->id.'" class="recipeNameEnter" target="_blank">'.$recipeelem->name.'</a>
                <span class="recipeIngestionEnter">'.$recipeelem->ingestion_text.'</span>
                <span class="recipeIngestionEnter" style="color: gray; font-size: 13px;">Кол-во продуктов: '.$recipeelem->count.'</span>
            </div>
           <img src="images/continue.png" class="setThisToList" width="15%" height="15%" alt="'.$recipeelem->ingestion_text.': '.$recipeelem->name.'">
        </div>
    </div>
                <hr class="recipeLine">
    ');
        }echo ('</div>');
    }
}
?>