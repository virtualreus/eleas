<?php
require "libs/rb.php";
R::setup('secret', 'no', 'no');
if(!R::testConnection()) die('No DB connection!');
?>