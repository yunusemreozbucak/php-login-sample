<?php

session_start();
$_SESSION = array();
session_destroy();   // çıkış yapıyoruz.
header("location: login.php");

?>