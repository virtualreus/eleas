<?php

$pagesubs_old = explode(', ', $user->subscribers);
$hissubs_old = explode(', ', $user->self_subscribe);

$pagesubs = array_filter($pagesubs_old, 'strlen');
$hissubs = array_filter($hissubs_old, 'strlen');

$recipestypes = array(
    "0" => "-не выбрано-",
    "1" => "Выпечка и десерты",
    "2" => "Основное блюдо",
    "3" => "Завтрак",
    "4" => "Обед",
    "5" => "Ужин",
    "6" => "Салат",
    "7" => "Морепродукты",
    "8" => "Сладкое",
    "9" => "Супы"
);

?>



<div id="popur">
    <div class="editButtons">
        <div id="inform_edit"></div>
        <textarea class="editDesc" placeholder="Введите описание:"><?=$user->description?></textarea>
        <button class="welcome-button3">Сохранить</button>
    </div>
    <img src="images/close.png" class="close-button" width="30px" height="30px">
</div>
<div id="hover"></div>


<div id="modalAddRecipe">
    <div class="addRecipesFields">
        <div id="inform_recipe"></div>
        <h3 class="addTitle">Создание мини-рецепта.</h3>
        <input type="text" id="nameOfRecipe" placeholder="Введите название"><br>

        <select id="selectZOYS">
            <option value="1" selected="selected">Завтрак</option>
            <option value="2">Обед</option>
            <option value="3">Ужин</option>
            <option value="4">Другое</option>
        </select>

    </div>
    <button class="recipeCont">Продолжить</button>
    <img src="images/close.png" width="30px" height="30px" class="close-adding">
</div>


<div id="modalAddCheckLists">
    <div class="addRecipesFields">
        <div id="inform_recipe"></div>
        <h3 class="addTitle">Создание чек-листа.</h3>
        <input type="text" id="nameOfList" placeholder="Введите название"><br>
    </div>

    <button class="listCont">Продолжить</button>
    <img src="images/close.png" width="30px" height="30px" class="close-adding_2">
</div>


<div id="informrec"></div>

<div id="modalCreateRecipe">
    <div class="addRecipesFields">
        <div id="inform_recipe"></div>
        <h3 class="addTitle">Создание рецепта.</h3>
        <input type="text" id="name_Recipe" placeholder="Введите название"><br>
    </div>

    <button class="createRecCont">Продолжить</button>
    <img src="images/close.png" width="30px" height="30px" class="close-recipe">
</div>







<div id="Profile">
    <div id="leftBar">
        <img src="images/logoFace.png" width="60%" style="margin-top: 15px;">

        <div class="profilenameMain">
            <?php if ($user->admin):?>
                <h2 class="Profilename1"><?=$username?><h2 class="Profilename2"><?=$username?></h2><?php if ($user->verification):?></h2>
                    <img src="images/verified.png" class="imageVerification">
                <?php endif;?>
            <?php else: ?>
                <h2 class="Profilename"><?=$username?></h2>
                <h2 class="Profilename3"><?=$username?></h2>
                    <?php if ($user->verification):?>
                    <img src="images/verified.png" class="imageVerification">
                <?php endif;?>
            <?php endif;?>
        </div>

        <div class="descBlock">
            <?php if ($user->description) : ?>
            <span class="profileDesc"><b style="word-break: break-word"><?=$user->description?></b></span>
            <?php else :?>
                <span class="profileDesc"><b style="word-break: break-word; color: gray">Пустое описание :D</b></span>
            <?php endif;?>
        </div>
        <button class="welcome-button2">Редактировать описание</button>

        <div class="subsBlock">
            <div class="subsElem">
                <span class="subsName">Подписчики</span>
                <span class="subsCount"><?=count($pagesubs)?></span>
            </div>
            <div class="subsElem">
                <span class="subsName">Подписки</span>
                <span class="subsCount"><?=count($hissubs)?></span>
            </div>
        </div>

    </div>


<div id="MainProfile">
        <div id="profileGreet">
            <h1 class="greetH1 textStyle">Добро пожаловать, <b><?=$username?></b>! </h1>
            <span class="emoji">&#129303;</span>
        </div>

            <div id="mainBlockProfile">
                <div class="profileBar">
                    <button class="buttonNavProfile buttonMiniRecipesGO">Мини-рецепты</button>
                    <button class="buttonNavProfile buttonRecipesGO">Рецепты</button>
                    <button class="buttonNavProfile navhover buttonCheckListsGO">Чек-листы</button>
                </div>

                <?php $checklists = R::findAll('checklists', 'authorid = ?', array($user->id)); ?>

                <div id="checkLists" style="display: flex">
                    <h1 class="recipe_prof_h1">Мои чек-листы:</h1>
                    <div id="checkListsBlock">
                        <?php        $n1 = 0;?>
                        <?php foreach (array_reverse($checklists) as $list) : $n1++;?>
                            <div class="checklistecho">

                                <hr class="recipeLine">
                                <div class="checklistrow">
                                    <img src="images/logo.png" class="" width="25%">
                                    <div class="checklisttext">
                                        <h3 class="recipe_prof_h3" style="padding: 0">Чек-лист</h3>
                                        <a href="/editchecklist?l=<?=$list->id?>" target="_blank" class="recipeEchoName"><b><?=$list->name?></b></a>
                                        <h3 class="recipe_prof_h3" style="padding: 0">Создано: <?=$list->date?></h3>
                                    </div>
                                </div>
                                <hr class="recipeLine">

                            </div>
                        <?php endforeach;?>

                        <?php         if($n1 == 0) {
                            echo '<hr class="recipeLine">
                    <div class="recipeNone"><h2 class="recipeNoneH2">Чек-листов пока нет :(</h2>
                    <span class="recipeNoneText">Самое время их добавить!</span></div>
                    <hr class="recipeLine">';
                        }?>


                    </div>
                    <button class="addCheckList">Добавить</button>
                </div>



                <div id="Recipes" style="display: none">
                    <?php $recipes = R::findAll('recipes', 'authorid = ?', array($user->id)); ?>
                    <h1 class="recipe_prof_h1">Мои рецепты:</h1>
                    <?php if ($recipes) : ?>
                    <div class="RecipesList_main">
                        <?php foreach (array_reverse($recipes) as $recipeitem) :?>
                            <a href="/editrecipe?r=<?=$recipeitem->id?>" target="_blank" style="background-image: url(images/recipes/<?=$recipeitem->logo?>);" class="recipeBlock_profile">
                                <div class="recipesList_Details">
                                    <span class="recipesList_desc"><?=$recipestypes[$recipeitem->categoryid]?></span>
                                    <span class="recipesList_name"><?=$recipeitem->name?></span>
                                    <span class="recipesList_date"><?=$recipeitem->date?></span>
                                </div>
                            </a>
                        <?php endforeach;?>
                    </div>
                    <?php else : ?>
                        <hr class="recipeLine">
                        <div class="recipeNone">
                            <h2 class="recipeNoneH2">Рецептов пока нет :(</h2>
                            <span class="recipeNoneText">Самое время их добавить!</span>
                        </div>
                        <hr class="recipeLine">
                    <?php endif;?>


                    <button class="createRecipe">Добавить</button>
                </div>





                <div id="miniRecipes" style="display: none">
                    <? $recipe = R::findAll('minirecipes', 'authorid = ?', array($user->id)); ?>
                    <div class="minirecipes_categories">
                        <h1 class="recipe_prof_h1">Мои мини-рецепты:</h1>
                        <div id="navButtonsMain">
                            <button class="buttonNavProfile navall navhover navMargin" >Все</button>
                            <button class="buttonNavProfile navzav navMargin">Завтраки</button>
                            <button class="buttonNavProfile navobed navMargin">Обеды</button>
                            <button class="buttonNavProfile navyjin navMargin">Ужины</button>
                            <button class="buttonNavProfile navsalat navMargin">Другое</button>
                        </div>
                    </div>


                <div id="navallblock">
                    <?php        $n = 0;?>
                    <?php foreach(array_reverse($recipe) as $item):
                    $n++;?>
                    <hr class="recipeLine">
                        <div class="recipe_main">
                            <div class="recipe_prof_info" style="">
                                <img src="images/logo.png" class="imageFixed">
                            <div class="recipe_prof_urls">
                                <span class="recipeEchoName2"><?=$item->ingestion_text?></span>
                                <a href="/editminirecipe?r=<?=$item->id?>" target="_blank" class="recipeEchoName2 recipeEchoLink"><b> <?=$item->name?></b></a>
                            </div>
                        </div>
                    <hr class="recipeLine">

                </div>
                    <?php endforeach;?>
                    <?php         if($n == 0) {
                        echo '<hr class="recipeLine">
<div class="recipeNone"><h2 class="recipeNoneH2">Мини-рецептов пока нет :(</h2>
<span class="recipeNoneText">Самое время их добавить!</span></div>
<hr class="recipeLine">';
                    }?>
                </div>
                <button class="addRecipe">Добавить</button>
                </div>
            </div>
</div>
</div>



</div>

<style>
    .subsBlock {
        margin-top: 10px;
        margin-left: 0;
        width: 90%;
    }

</style>