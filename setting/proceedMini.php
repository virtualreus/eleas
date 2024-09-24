<?php
require "../db.php";
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

$recipe = R::findAll('minirecipes', 'authorid = ?', array($user->id));

$whatis = $_POST['perm'];
if (isset($_POST)) {
    if ($whatis == "navall") {
        $n = 0;
        foreach (array_reverse($recipe) as $item) {
                $n++;
                echo '<hr class="recipeLine">
<div class="recipe_main">
    <div class="recipe_prof_info">
                                <img src="images/logo.png" class="imageFixed">
    <div class="recipe_prof_urls">
        <span class="recipeEchoName2">'.$item->ingestion_text.'</span>
        <a href="/editminirecipe?r=' . $item->id . '" target="_blank" class="recipeEchoName2 recipeEchoLink"><b>' . $item->name . '</b></a>
    </div>
</div>
<hr class="recipeLine">

</div>';
        }
        if($n == 0) {
            echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
        }
    }
    elseif($whatis == "navzav") {
        $n = 0;
        foreach (array_reverse($recipe) as $item) {
            if ((int)$item->ingestion == 1) {
                $n++;
                echo '<hr class="recipeLine">
<div class="recipe_main">
    <div class="recipe_prof_info">
                                <img src="images/logo.png" class="imageFixed">
    <div class="recipe_prof_urls">
        <span class="recipeEchoName2">'.$item->ingestion_text.'</span>
        <a href="/editminirecipe?r=' . $item->id . '" target="_blank" class="recipeEchoName2 recipeEchoLink"><b>' . $item->name . '</b></a>
    </div>
</div>
<hr class="recipeLine">

</div>';
            }
        }
        if($n == 0) {
            echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
        }
    }
    elseif($whatis == "navobed") {
        $n = 0;
        foreach (array_reverse($recipe) as $item) {
            if ((int)$item->ingestion == 2) {
                $n++;
                echo '<hr class="recipeLine">
<div class="recipe_main">
    <div class="recipe_prof_info">
                                <img src="images/logo.png" class="imageFixed">
    <div class="recipe_prof_urls">
        <span class="recipeEchoName2">'.$item->ingestion_text.'</span>
        <a href="/editminirecipe?r=' . $item->id . '" target="_blank" class="recipeEchoName2 recipeEchoLink"><b>' . $item->name . '</b></a>
    </div>
</div>
<hr class="recipeLine">

</div>';
            }
        }
        if($n == 0) {
            echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
        }
    }

    elseif($whatis == "navyjin") {
        $n = 0;
        foreach (array_reverse($recipe) as $item) {
            if ((int)$item->ingestion == 3) {
                $n++;
                echo '<hr class="recipeLine">
<div class="recipe_main">
    <div class="recipe_prof_info">
                                <img src="images/logo.png" class="imageFixed">
    <div class="recipe_prof_urls">
        <span class="recipeEchoName2">'.$item->ingestion_text.'</span>
        <a href="/editminirecipe?r=' . $item->id . '" target="_blank" class="recipeEchoName2 recipeEchoLink"><b>' . $item->name . '</b></a>
    </div>
</div>
<hr class="recipeLine">

</div>';
            }
        }
        if($n == 0) {
            echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
        }
    }

    elseif($whatis == "navsalat") {
        $n = 0;
        foreach (array_reverse($recipe) as $item) {
            if ((int)$item->ingestion == 4) {
                $n++;
                echo '<hr class="recipeLine">
<div class="recipe_main">
    <div class="recipe_prof_info">
                                <img src="images/logo.png" class="imageFixed">
    <div class="recipe_prof_urls">
        <span class="recipeEchoName2">'.$item->ingestion_text.'</span>
        <a href="/editminirecipe?r=' . $item->id . '" target="_blank" class="recipeEchoName2 recipeEchoLink"><b>' . $item->name . '</b></a>
    </div>
</div>
<hr class="recipeLine">

</div>';
            }
        }
        if($n == 0) {
            echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
        }
    }
    $whatis = "";
}
?>