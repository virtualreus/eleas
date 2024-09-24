<?php
require "../db.php";

if (isset($_POST)) {
    $id = $_POST['id'];
    $recipe = R::findOne('checklists', 'id = ?', array($id));
    $recipe_base = R::findOne('userdata', 'typeletter = ? AND parentid = ?', array('l', $id));
    R::trash($recipe);
    R::trash($recipe_base);
    echo ('<script>note({
                  content: `<b><span class="object-alert">Редактирование чек-листа</span><br></b><b>Чек-лист успешно удалён!</b>`,
                  type: "success",
                  time: 10
                });
                 setTimeout(() => { window.location.reload(); }, 1000);
                </script>');
}

?>