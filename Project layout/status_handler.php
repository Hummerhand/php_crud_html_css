<?php

    session_start();

    require "functions.php";

    # Получение данных с формы status.php
    $id = $_POST['id'];
    $status = $_POST['status'];


    # Изменение статуса работника в БД
    update_employee_by_status($id, $status);
    

    # Сообщение об успешном изменении Общей информации работника
    set_flash_message('success', 'Профиль успешно обновлен');


    # Перенаправляем на страницу ввода Логин/пароля
    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_profile.php?id='.$id);
?>