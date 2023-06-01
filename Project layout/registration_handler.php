<?php

    session_start();

    require "functions.php";

    $email = $_POST['userEmail'];
    $password = $_POST['userPassword'];


    # Получаем рабочего по email
    $employee = get_employee_by_email($email);


    # Если такой email занят, перенаправляем назад
    if (!empty($employee)) {

        set_flash_message('danger', ' Введенная учетная запись уже существует');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_register.php');
    }


    # Если логин пуст, тогда добавляем рабочего в БД
    registration_employee($email, $password);


    # Сообщение об успешной регистрации
    set_flash_message('success', 'Учетная запись успешно зарегистрирована');


    # Перенаправляем на страницу ввода Логин/пароля
    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_login.php');

?>