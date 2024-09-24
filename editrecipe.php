<?php
require "db.php";
require "mobiledetect.php";
$detect = new Mobile_Detect;
if(!R::testConnection()) die('No DB connection!');


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


$id_recipe = $_GET['r'];
$recipeobject = R::findOne('recipes', 'id = ?', array($id_recipe));

if($user->id != $recipeobject->authorid) {
    header("Location: /profile");
}

function top($title, $cookie) {
    include "page/top.php";
} top("Редактирование рецепта", $user);


$recipestepstext = array_filter(explode("-|-", $recipeobject->data), 'strlen');
$recipestepsphotoes = array_filter(explode("|||", $recipeobject->dataphotoes), 'strlen');


$recipestypes = array(
    "0" => "Не выбрано",
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


<?php
function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}
function str2url($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}


$msg = "";
if (isset($_POST['upload'])) {
    $filename = $_FILES["logo"]["name"];
    if ($filename) {
        $tempname = $_FILES["logo"]["tmp_name"];
        $filename = $user->login."_".str2url($recipeobject->name)."_logo_".$filename;
        $folder = "images/recipes/" . $filename;

        $recipeobject->logo = $filename;
        R::store($recipeobject);

        if (move_uploaded_file($tempname, $folder)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }
    }
    $old = array_filter(explode("|||", $recipeobject->dataphotoes), 'strlen');
    $photoesnames = array();
    for ($i = 0; $i <  $recipeobject->count_steps; $i++) {
        $filenameStep = $_FILES["ingredPhoto".$i.""]["name"];
        if($filenameStep) {
            $tempnameStep = $_FILES["ingredPhoto".$i.""]["tmp_name"];
            $filenameStep = $user->login."_".str2url($recipeobject->name)."_step".$i."_".$filenameStep;
            $folderStep = "images/recipes/" . $filenameStep;

            array_push($photoesnames,$filenameStep);
            if (move_uploaded_file($tempnameStep, $folderStep)) {
                $msg = "Image uploaded successfully";
            } else {
                $msg = "Failed to upload image";
            }

        } else {
            if ($old) {
                array_push($photoesnames, $old[$i]);
//                $photoesnames = ($photoesnames . $old[$i - 1]);
            } else {
                array_push($photoesnames,"NOPHOTO");
            }

        }
    }
    $recipeobject->dataphotoes = implode("|||", $photoesnames);;
    R::store($recipeobject);

}

?>



<div id="id" style="display: none"><?=$recipeobject->id?></div>
<div id="informik"></div>
<div id="GreetEdit">
    <h1 class="Editname">Редактирование:  <?=$recipeobject->name?> </h1><a class="gtRecipe" href="/recipe?r=<?=$recipeobject->translated_name?>">Просмотреть</a>
</div>

<div id="EditListTitles">
    <span class="newNameRec_span">Название: </span> <input type="text" id="newRecName" value="<?=$recipeobject->name?>" maxlength="65" placeholder="Название:">
</div>


    <form method="POST" action="" enctype="multipart/form-data" style="padding-bottom: 10%">
        <div id="recipe_MainBlock">
            <?php if ($recipeobject->logo) :?>
                <h2 class="RecH2">Титульная фотография</h2>
                <img src="/images/recipes/<?=$recipeobject->logo?>" class="logoRecPhoto" alt="Титульная фотография">
            <?php else:?>
                <h2 class="RecH2">Выберите титульную фотографию</h2>
                <img src="/images/logo.png" width="200px" style="margin-bottom: 1%" alt="Титульная фотография">
            <?php endif;?>

                <div class="field__wrapper">
                    <input type="file" name="logo" id="field__file-2" value="" class="field field__file">
                    <label class="field__file-wrapper" for="field__file-2">
                        <div class="field__file-fake">Выберите фотографию</div>
                        <div class="field__file-button">Выбрать</div>
                    </label>
                </div>

            <h2 class="RecH2">Описание рецепта: </h2>
            <textarea id="recipeDescArea" placeholder="Введите описание"><?=$recipeobject->desc?></textarea>

            <div class="recipeParams">
                <h2 class="RecH2">Параметры рецепта</h2>
                <div class="paramsLine">

                    <div class="paramsRecElem">
                        <span class="paramsSpanDesc">Введите кол-во ингредиентов</span>
                        <input type="text" id="colsOfIngred" placeholder="Кол-во" value="<?=$recipeobject->count_ingreds?>">
                    </div>

                    <div class="paramsRecElem">
                        <span class="paramsSpanDesc">Введите кол-во шагов</span>
                        <input type="text" id="colsOfSteps" placeholder="Кол-во" value="<?=$recipeobject->count_steps?>">
                    </div>

                    <div class="paramsRecElem">
                        <span class="paramsSpanDesc">Выберете тип рецепта</span>
                        <select id="selectRecipeType">
                            <option value="<?=$recipeobject->categoryid?>" selected="selected"><?=$recipestypes[$recipeobject->categoryid]?></option>
                            <option value="1">Выпечка и десерты</option>
                            <option value="2">Основное блюдо</option>
                            <option value="3">Завтрак</option>
                            <option value="4">Обед</option>
                            <option value="5">Ужин</option>
                            <option value="6">Салат</option>
                            <option value="7">Морепродукты</option>
                            <option value="8">Сладкое</option>
                            <option value="9">Супы</option>
                        </select>

                    </div>

                </div>

            </div>

            <div id="ingredsBlock">
                <?php
                $ingreds_base = explode('-|-',$recipeobject->ingreds);
                for ($i = 1; $i <= $recipeobject->count_ingreds; $i++) :
                    $ingritem = $ingreds_base[$i - 1];
                    ?>
                    <div class="ingBlockFlex">
                        <span class="recminidesc">Ингредиент № <?=$i?>: </span>
                        <?php
                        $sepingritem = explode("|d|s|", $ingritem);
                        if($sepingritem[0] == "NONE") :?>
                            <input type="text" maxlength="20" class="mr_ingredStyle mr_Ingred<?=$i?>" placeholder="Введите название" value="">
                        <?php else:?>
                            <input type="text" maxlength="20" class="mr_ingredStyle mr_Ingred<?=$i?>" placeholder="Введите название" value="<?=$sepingritem[0]?>">
                        <?php endif;?>

                        <span class="recminidesc">Дозировка: </span>
                        <?php if($sepingritem[1] == "NONEDOZ") :?>
                            <input type="text" maxlength="10" class="mr_kolvoStyle mr_Kolvo<?=$i?>" placeholder="Кол-во" value="">
                        <?php else:?>
                            <input type="text" maxlength="10" class="mr_kolvoStyle mr_Kolvo<?=$i?>" placeholder="Кол-во" value="<?=$sepingritem[1]?>">
                        <?php endif;?>
                    </div>
                <?php endfor;?>
            </div>

            <div id="stepsIdBlock">
                <?php for($i = 1; $i <= $recipeobject->count_steps; $i++) :
                    $tempvaluetext = $recipestepstext[$i - 1];
                    $tempvaluephoto = $recipestepsphotoes[$i - 1];
                    ?>
                    <div class="stepMainBlock">
                        <div class="stepBlockPhoto">

                            <?php if ($tempvaluephoto == "NOPHOTO"):?>
                                <img src="/images/logo.png" style="margin: auto;" width="240px;" alt="Титульная фотография">
                            <?php else: ?>
                                <img src="/images/recipes/<?=$tempvaluephoto?>" style="margin: auto;" width="240px;" alt="Титульная фотография">
                            <?php endif;?>

                            <input type="file" class="inputPhoto" name="ingredPhoto<?=$i - 1?>">


                        </div>

                        <?php if ($tempvaluetext == 'NONE'): ?>
                            <textarea class="getRecDescStyle rec_Desc<?=$i?>" placeholder="Введите описание шага"></textarea>
                        <?php else:?>
                            <textarea class="getRecDescStyle rec_Desc<?=$i?>" placeholder="Введите Введите описание шага"><?=$tempvaluetext?></textarea>
                        <?php endif;?>

                    </div>
                <?php endfor;?>
            </div>
            <div>
                <button type="submit" id="saveRecipeEdits" name="upload">Обновить</button>
            </div>
        </div>
    </form>



<script>
    let elem = document.getElementById("nav-mainpage");
    elem.classList.remove('current')

    let elem2 = document.getElementById("nav-mainpage-mob");
    elem2.classList.remove('current')


    $(document).ready(function () {
        $('#colsOfIngred').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

        $('#colsOfSteps').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

    });
</script>

<script>
    let fields = document.querySelectorAll('.field__file');
    Array.prototype.forEach.call(fields, function (input) {
        let label = input.nextElementSibling,
            labelVal = label.querySelector('.field__file-fake').innerText;

        input.addEventListener('change', function (e) {
            let countFiles = '';
            if (this.files && this.files.length >= 1)
                countFiles = this.files.length;

            if (countFiles)
                label.querySelector('.field__file-fake').innerText = 'Выбрано файлов: ' + countFiles;
            else
                label.querySelector('.field__file-fake').innerText = labelVal;
        });
    });
    $( function() {
        let availableTags = [
            "Яйца куриные",
            "Сахар",
            "Мука",
            "Какао",
            "Масло сливочное",
            "Творог",
            "Вода",
            "Масло подсолнечное",
            "Масло растительное",
            "Масло оливковое",
            "Сливки",
            "Взбитые сливки",
            "Шоколад белый",
            "Шоколад темный",
            "Шоколад молочный",
            "Желатин",
            "Кефир",
            "Молоко",
            "Соль",
            "Разрыхлитель",
            "Мёд",
            "Твороженный сыр",
            "Сгущенка",
            "Сыр Маскарпоне",
            "Печенье савоярди",
            "Сахарная пудра",
            "Кофе",
            "Ванилин",
            "Сода",
            "Желток",
        ];
        $(".mr_ingredStyle").autocomplete({
            source: availableTags
        });
    } );

</script>

