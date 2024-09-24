<?php
if (isset($_POST)) {
    $querry = $_POST['querry'];
    $querry_code = urlencode($querry);
    echo '<script>window.location.href = "http://eleas.ru/search?q='.$querry_code.'";</script>';
}
?>