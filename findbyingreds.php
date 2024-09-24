<?
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
} top("Поиск по ингредиентам", "Добро пожаловать на eleas.ru! Раздел: Поиск по ингредиентом. Зарегестрируйтесь, и вы сможете добавлять свои чек-листы и рецепты, наблюдать за другими, делиться! Теперь все рецепты и меню у вас под рукой! сайт рецептов с пошаговым фото, сайты рецептов самые лучшие, сайты рецептов, сайты меню, сайты где можно составить меню, сайты где можно составить рецепты, рецепты",
    "Рецепт, готовить, ингредиенты, поиск по ингредиентам, как приготовить, торт, десерт, десерты, выпечка, завтрак, обед, ужин, готовка, ужин, салат, сладко, сладенькое сайт рецептов с пошаговым фото, сайты рецептов самые лучшие, сайты рецептов, сайты меню, сайты где можно составить меню, сайты где можно составить рецепты, рецепты", $user);
