<?

    session_start();

    require "functions.php";

    # Получение данных с формы security.php
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    

    # Изменение полей работника в БД
    update_employee_by_security_inf($id, $email, $password, $confirm_password);
    

    # Сообщение об успешном изменении Общей информации работника
    set_flash_message('success', 'Профиль успешно обновлен');


    # Перенаправляем на страницу ввода Логин/пароля
    redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_profile.php?id='.$id);


?>    