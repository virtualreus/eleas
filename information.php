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

function top($title, $description, $keywords, $cookie) {
    include "page/top.php";
} top("Информация", "Добро пожаловать на eleas.ru! Зарегестрируйтесь, и вы сможете добавлять свои чек-листы и рецепты, наблюдать за другими, делиться! Теперь все рецепты и меню у вас под рукой!",
    "Рецепт, готовить, как приготовить, торт, десерт, десерты, выпечка, завтрак, обед, ужин, готовка, ужин, салат, сладко, сладенькое", $user);
?>

<div id="informationBlock">
    <h1 class="informationH1">Информация eleas.ru</h1>

    <div class="informationBlockFlex">
        <div class="infoText">
            <span class="infoTextSpan">
                Сайт eleas.ru был создан и поддерживается 2 марта 2022 года  для системацизации и для хранения ваших любимых рецептов и чек-листов в одном месте.<br><br>
                Вы можете создавать мини-рецепты, содержащие лишь список ингредиентов и способ приготовления, а из них уже
                составлять целые чек-листы (меню) на неделю. <br><br>   Так же тут вы можете создавать полноценные пошаговые рецепты с картинками или без них.
                Доступна обратная связь, отзывы, сохранения в избранное и многое-многое другое!
            </span>
        </div>
        <div class="infoLinks">
            <span class="linksHeader">Контакты для связи</span>
            <span class="linksLabel">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                e-mail: <a class="linkColor" href="mailto:nt3008@yandex.ru">nt3008@yandex.ru</a>
            </span>

            <span class="linksLabel">
                <i class="fa fa-vk" aria-hidden="true"></i>
                VK: <a class="linkColor" target="_blank" href="https://vk.com/virtualreus">virtualreus</a>
            </span>

            <span class="linksLabel">
                <i class="fa fa-telegram" aria-hidden="true"></i>
                TG: <a class="linkColor" target="_blank" href="https://t.me/n_reus">n_reus</a>
            </span>

            <span class="linksLabel">
                Russia, Moscow
            </span>
        </div>
    </div>

</div>
