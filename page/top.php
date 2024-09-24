<?php
mb_internal_encoding("UTF-8");
?>

<!DOCTYPE html>
<html>
<head itemscope itemtype="http://schema.org/WPHeader">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/mainpage.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/alerts.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/mediamobile.css">
    <link rel="stylesheet" href="css/recipes.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/user.css">
    <link rel="stylesheet" href="css/recipemain.css">
    <link rel="stylesheet" href="css/media.css">
    <script src="/scripts/auth.js"></script>
    <script src="/scripts/alerts.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">

    <link rel="icon" href="https://eleas.ru/favicon.ico" type="image/x-icon">

    <link rel="manifest" href="images/favicon/site.webmanifest">
    <title itemprop="headline"><?=$title?> | eleas.ru</title>
    <meta charset="utf-8">
    <meta name="yandex-verification" content="937ab42b6dde148e" />
    <meta itemprop="description" name="description" content="<?=$description?>">
    <meta itemprop="keywords" name="keywords" content="<?=$keywords?>">
    <meta name="author" content="Virtualreus">
    <script async src="https://cse.google.com/cse.js?cx=050ee3ae559dd2489"></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(87677756, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/87677756" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <meta name="google-site-verification" content="NsYXD4MGH5k8DA86WxGaVZPuDIRez_veUuVcP8uc8mc" />
</head>

<body>
<div id="Header">
    <div id="hover"></div>
    <div id="headerTitle">
        <a href="/"><img src="images/logo.png" class="logoImage"></a>
        <div class="logoFont">
            <span class="logoName"><b>eleas</b></span><br>
        </div>
    </div>

    <div id="Navigation">
        <ul class="menu-main" itemscope itemtype="http://schema.org/SiteNavigationElement">
            <?php if ($cookie) : ?>
                <li><a itemprop="url" href="http://eleas.ru" id="nav-mainpage" class="current">Главная</a></li>
                <li><a itemprop="url" href="http://eleas.ru/profile" id="nav-mypage">Моя страница</a></li>
                <li><a itemprop="url" href="http://eleas.ru/saved" id="nav-saved">Избранное</a></li>
                <li><a itemprop="url" href="http://eleas.ru/recommend" id="nav-reco">Рекомендованное</a></li>
                <?php if ($cookie->admin == 2) : ?>
                    <li><a href="https://eleas.ru/findbyingreds" class="nav-findbyingr">Поиск по ингредиентам</a></li>
                <?php endif;?>
                <li><a itemprop="url" id="nav-search"><i class="fa fa-search"></i></a></li>
                <li><a itemprop="url" href="https://eleas.ru/information" id="nav-info"><i class="fa fa-info-circle"></i></a></li>
            <?php else:  ?>
                <li><a href="http://eleas.ru" id="nav-mainpage" class="current">Главная</a></li>
                <li><a href="http://eleas.ru/recommend" id="nav-reco">Рекомендованное</a></li>
<!--                <li><a class="nav-findbyingr">Поиск по ингредиентам</a></li>-->
                <li><a id="nav-search"><i class="fa fa-search"></i></a></li>
                <li><a href="https://eleas.ru/information" id="nav-info"><i class="fa fa-info-circle"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>


    <div class="entrance">
        <?php if (!$cookie) : ?>
            <a href="/signin" class="floating-button">авторизация</a>
            <a href="/signup" class="floating-button">регистрация</a>
        <?php else:  ?>
            <a href="account/logout.php" class="floating-button">выйти</a>
        <?php endif; ?>
    </div>
</div>


<div id="Navigation-mobile" style="display: none">
    <ul class="menu-main" itemscope itemtype="http://schema.org/SiteNavigationElement">
        <?php if ($cookie) : ?>
            <li><a itemprop="url" href="https://eleas.ru" id="nav-mainpage-mob" class="current">Главная</a></li>
            <li><a itemprop="url" href="https://eleas.ru/profile" id="nav-mypage-mob">Моя страница</a></li>
            <li><a itemprop="url" href="https://eleas.ru/saved" id="nav-saved-mob">Избранное</a></li>
            <li><a itemprop="url" href="https://eleas.ru/recommend" id="nav-reco-mob">Рекомендованное</a></li>
<!--            <li><a href="https://eleas.ru/findbyingreds" class="nav-findbyingr">Поиск по ингредиентам</a></li>-->
            <li><a itemprop="url" id="nav-search-mob"><i class="fa fa-search"></i></a></li>
            <li><a itemprop="url" href="https://eleas.ru/information" id="nav-info-mob"><i class="fa fa-info-circle"></i></a></li>
        <?php else:  ?>
            <li><a itemprop="url" href="https://eleas.ru" id="nav-mainpage-mob" class="current">Главная</a></li>
            <li><a itemprop="url" href="https://eleas.ru/recommend" id="nav-reco-mob">Рекомендованное</a></li>
<!--            <li><a class="nav-findbyingr">Поиск по ингредиентам</a></li>-->
            <li><a itemprop="url" href="https://eleas.ru/information" id="nav-search-mob"><i class="fa fa-search"></i></a></li>
            <li><a itemprop="url" href="https://eleas.ru/information" id="nav-info"><i class="fa fa-info-circle"></i></a></li>
        <?php endif; ?>
    </ul>
</div>

<div id="modalSearch">
    <img class="close-search" src="images/close.png">
    <h3 class="addTitleSearch">Поиск.</h3>
    <input type="text" id="searchField" placeholder="Поиск:">
    <button id="findButton">Найти</button>
</div>
<div id="informing"></div>