<?php
require "db.php";
if(!R::testConnection()) die('No DB connection!');
$user = R::findOne('users', 'id = ?', array($_SESSION['user']->id));

$idRecipe = $_GET["r"];
$recipe = R::findOne('minirecipes', 'id = ?', array($idRecipe));

function top($title, $description, $cookie) {
    include "page/top.php";
} top($recipe->name, "Данный мини-рецепт был создан ".$recipe->author.". В дальнейшем он будет использоваться в чек-листах! Содержит ".$recipe->count." ингредиентов." ,$user);
?>
<?php function ParseMessageChat($str){
        $search = ["\n"];
        $replace = ["<br>"];
        $str = str_replace($search, $replace, $str);
        return $str;
    }?>

<div id="GreetEdit">
    <h1 class="Editname"><?=$recipe->ingestion_text?>: <?=$recipe->name?> | </h1><span class="minidesc">Автор: <?=$recipe->author?></span>
</div>
<div id="viewRecipeContent">

    <div id="viewProducts">

        <div class="productsEching">
            <span class="productsMiniTitle" style="margin: 0">Продукты:</span>
            <div id="productsMiniEcho">
                <?php foreach (explode("<br>",$recipe->ingreds_base) as $string) :?>
                    <?php if ($string): $curringr = explode(' - ',$string)?>
                        <?php if($curringr[0] == "") {
                            $curringr[0] = "<span style='color: gray; font-size: 15px;'>Не указан</span>";}
                        if($curringr[1] == "") {
                            $curringr[1] = "<span style='color: gray; font-size: 15px;'>Не указана</span>";}
                        ?>
                        <span><?=$curringr[0]?> - <?=$curringr[1]?><br></span> <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>

        <div class="algoEcho">
            <span class="productsMiniTitle" style="margin: 0">Рецепт:</span><br><?=ParseMessageChat($recipe->algorithm)?></div>
    </div>
<!--    <button id="saveMiniRecipeToPDF">Сохранить</button>-->
</div>