<?php
require "../db.php";

if (isset($_POST)) {
    $id = $_POST['id'];
    $recipe = R::findOne('minirecipes', 'id = ?', array($id));
    R::trash($recipe);
    exit ('<script>note({
                  content: `<b><span class="object-alert">Редактирование рецепта</span><br></b><b>Рецепт успешно удалён!</b>`,
                  type: "success",
                  time: 10
                });
                 setTimeout(() => { window.location.reload(); }, 1000);
                </script>');

}

?>