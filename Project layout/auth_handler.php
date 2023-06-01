<?php

    session_start();
    
    require "functions.php";

    $email = $_POST['enteredEmail'];
    $password = $_POST['enteredPassword'];

    // Проверяет совпадают ли введенный Логин и пароль с данными из БД, если да перенаправляет, если нет,
    // обратно перенаправляет на страницу ввода
    if (login($email, $password)) {

        set_flash_message('success', 'Вы авторизованы!');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/users.php');

    } else {

        set_flash_message('danger', ' Неверный логин или пароль');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_login.php');

    }

?>