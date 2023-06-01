<?php

    session_start();

    require "functions.php";

    # Получение данных с формы media.php
    $id = $_GET['id'];
      

    # Изменение статуса работника в БД
    delete_employee_by_id($id);
    

    # Сообщение об успешном изменении Общей информации работника
    set_flash_message('success', 'Учетная запись успешно удалена');


    # Перенаправляем на страницу ввода Логин/пароля
    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/users.php');
?>