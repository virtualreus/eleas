<?php
require "db.php";
require "mobiledetect.php";
$detect = new Mobile_Detect;
if(!R::testConnection()) die('No DB connection!');



if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
    if (!empty($_COOKIE['login']) and !empty($_COOKIE['key']) ) {
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
$idRecipe = $_GET["r"];
$recipe = R::findOne('minirecipes', 'id = ?', array($idRecipe));
if($userLogin != $recipe->author) {
    header("Location: /profile");
}

$recipes_array = explode("<br>", $recipe->ingreds_base);

function top($title, $cookie) {
    include "page/top.php";
} top("Редактирование мини-рецепта", $user);

function greetEdit($title) {
    include "page/greetingEdit.php";
}
greetEdit($recipe->name);
?>
<div id="EditMain">
    <span id="id" style="display: none"><?=$recipe->id?></span>


    <div id="mainEdit">
        <div class="RecipeBlocks">
            <div class="prop" style="padding-bottom: 5%">
                <span class="propNameRec propNameRecMain"><b>Название:</b></span>
                <input type="text" class="fieldRecipeEdit" id="newnamerec" value="<?=$recipe->name?>" placeholder="Название рецепта">
            </div>

            <div class="prop"><span class="propNameRec"><b>Тип:</b></span><select id="selectZOYSedit">
                <?php if ((int)$recipe->ingestion == 1) :?>
                    <option value="1" selected="selected">Завтрак</option>
                <?php else:?>
                    <option value="1" >Завтрак</option>
                <?php endif;?>

                <?php if ((int)$recipe->ingestion == 2) :?>
                    <option value="2" selected="selected">Обед</option>
                <?php else:?>
                    <option value="2" >Обед</option>
                <?php endif;?>

                <?php if ((int)$recipe->ingestion == 3) :?>
                    <option value="3" selected="selected">Ужин</option>
                <?php else:?>
                    <option value="3" >Ужин</option>
                <?php endif;?>

                <?php if ((int)$recipe->ingestion == 4) :?>
                    <option value="4" selected="selected">Другое</option>
                <?php else:?>
                    <option value="4" >Другое</option>
                <?php endif;?>
                </select></div> <br>

            <span class="propNameRec propNameRecMain"><b>Ингредиенты:</b></span><button style="margin-left: 2%" id="addIngr">+</button><button style="margin-left: 2%" id="delIngr">-</button>

            <div class="prop" style="display: flex; flex-direction: column">
                <div class="ingredsMain">
                    <div class="ingreds">

                        <?php for ($i = 1; $i <= $recipe->count; ++$i):
                            $nameoffield = "ingr" . $i;
                            $recipeingreds = explode(' - ', $recipes_array[$i - 1]);
                             ?>
                            <div class="ingredNameAndProps"><span class="ingredTitle"><b><?=$i?>:</b> </span>

                            <input type="text" class="nameOfIngred ingred<?=$i?>" id="newnamerec" value="<?=$recipeingreds[0]?>" placeholder="Ингредиент" maxlength="20">
                            <input type="text" class="dozaOfIngred doza<?=$i?>" value="<?=$recipeingreds[1]?>" placeholder="Доза" maxlength="8"></div>
                        <?php endfor; ?>


                        <span id="n_count" style="display: none"><?=$recipe->count?></span>
                    </div>

                </div>

            </div>

            <div id="inform_edit"></div>
        </div>
        <div class="AlgorithmMini">
            <span class="propNameRec propNameRecMain"><b>Краткий рецепт приготовления:</b></span>
            <textarea id="algoEdit"><?=$recipe->algorithm?></textarea>
        </div>
    </div>

    <div id="informDel"></div>
    <div class="buttonsEditRec">
        <a href="/viewminirecipe.php?r=<?=$idRecipe?>" target="_blank" class="viewRecipe">Просмотреть -></a>
        <button class="deleteRecipe">Удалить рецепт</button>
        <button id="saveEdits">Сохранить</button>
    </div>
</div>

<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')
</script>