<?php
require "../db.php";
if (isset($_POST)) {
    $id = $_POST['id'];
    $recipe = R::findOne('minirecipes', 'id = ?', array($id));
    $ingreds = $_POST['ingreds'];
    $recipe->ingreds_base = $ingreds;
    R::store($recipe);

    $recipes_array = explode("<br>", $ingreds);

    if ($recipe->count == 1) {
        for ($i = 1; $i < $recipe->count + 1; ++$i){
            $nameoffield = "ingr".$i;
            echo '<div class="ingredNameAndProps"><span class="ingredTitle"><b>'.$i.':</b> </span>
            <input type="text" class="nameOfIngred ingred'.$i.'" id="newnamerec" value="" placeholder="Ингредиент">
         <input type="text" class="dozaOfIngred doza'.$i.'" value="'.$recipe->$nameoffield.'" placeholder="Доза"></div>';
        }
        echo '<span id="n_count" style="display: none">'.$recipe->count.'</span>';


    } else {
        $recipe->count--;
        R::store($recipe);
        for ($i = 1; $i < $recipe->count + 1; ++$i) {
            $nameoffield = "ingr" . $i;
            $recipeingreds = explode(' - ', $recipes_array[$i - 1]);
            echo '<div class="ingredNameAndProps"><span class="ingredTitle"><b>' . $i . ':</b> </span>
            <input type="text" class="nameOfIngred ingred' . $i . '" id="newnamerec" value="'.$recipeingreds[0].'" placeholder="Ингредиент">
            
         <input type="text" class="dozaOfIngred doza' . $i . '" value="'.$recipeingreds[1].'" placeholder="Доза"></div>';
        }
        echo '<span id="n_count" style="display: none">' . $recipe->count . '</span>';


    }
}
?>