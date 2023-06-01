<?php

    session_start();

    require "functions.php";

    unset($_SESSION['employee']);

    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_login.php');

?>