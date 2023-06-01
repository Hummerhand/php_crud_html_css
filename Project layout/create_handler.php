<?php

    session_start();

    require "functions.php";


    $employeeName = $_POST['employeeName'];
    $jobTitle = $_POST['jobTitle'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $image = $_FILES['image'];
    

    # Получаем рабочего по email
    $employee = get_employee_by_email($email);

    
    # Если такой email занят, перенаправляем назад на страницу добавления нового работника
    if (!empty($employee)) {

        set_flash_message('danger', ' Введенная учетная запись уже существует');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/create_user.php');
    }


    # Если логин пуст, тогда добавляем нового рабочего в БД
    add_new_employee($employeeName, $jobTitle, $phone, $address, $email, $password, $status, $image);

    

    # Сообщение об успешной регистрации
    set_flash_message('success', 'Учетная запись успешно зарегистрирована и добавлена в БД');


    # Перенаправляем на страницу ввода Логин/пароля
    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/users.php');



?>