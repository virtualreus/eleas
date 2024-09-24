<?php
date_default_timezone_set('UTC');
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

$_monthsList = array("-01" => "января", "-02" => "февраля",
    "-03" => "марта", "-04" => "апреля", "-05" => "мая", "-06" => "июня",
    "-07" => "июля", "-08" => "августа", "-09" => "сентября",
    "-10" => "октября", "-11" => "ноября", "-12" => "декабря"
);

$user = R::findOne('users', 'id = ?', array($_SESSION['id']));
if (isset($_POST)) {
    $postid = explode("-", $_POST['recipeid']);
    $id = end($postid);
    $review_text = $_POST['review_text'];
    $date = date("d-mH:i");
    $recipeobject = R::findOne('recipes', 'id = ?', [$id]);
    $_mD = date("-m"); //для замены
    $currentDate = str_replace($_mD, " ".$_monthsList[$_mD]." ", $date);
    if ($user) {
        $review = R::dispense('reviews');
        $review->recipeid = $id;
        $review->authorid = $user->id;
        $review->author = $user->login;
        $review->date = $currentDate;
        $review->data = $review_text;
        R::store($review);

        echo ('<script>note({
                  content: `<b><span class="object-alert">Рецепты</span><br></b><b>Вы успешно оставили отзыв!</b>`,
                  type: "success",
                  time: 2
                });
                </script>');

    }
    $reviews = R::findAll('reviews', 'recipeid = ?' , [$recipeobject->id]);
    foreach (array_reverse($reviews) as $review) {
        $tempuser = R::findOne('users', 'id = ?', [$review->authorid]);
        echo '
                <div class="reviewBlock">
                    <div class="reviewTop"><a class="reviewAuthor" href="/user?u='.$tempuser->login.'">'.$tempuser->login.'</a>';
        if ($tempuser->verification == 1) {
            echo '<img src="images/verified.png" width="40px" height="40px">';
        }

        echo '<span class="reviewDate">'.$review->date.'</span>
                    </div>
                    <div class="reviewBottom">'.$review->data.'</div>
                </div>';
    }


}


?>
<!---->
<!--                    '.if ($tempuser->verification == 1) {.'-->
<!--                        <img src="images/verified.png" width="40px" height="40px">-->
<!--                    '.}.'-->
