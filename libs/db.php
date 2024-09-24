<?php
require "rb.php";
R::setup('mysql:host=localhost;dbname=project', 'nt_3008', 'cJ5rP8uT');
if(!R::testConnection()) die('No DB connection!');

?>