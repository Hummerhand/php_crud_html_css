<?

session_start();

require "functions.php";

# Получение данных с формы edit.php
$id = $_POST['id'];
$employeeName = $_POST['employeeName'];
$jobTitle = $_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];


# Изменение полей работника в БД
update_employee_by_general_inf($id, $employeeName, $jobTitle, $phone, $address);


# Сообщение об успешном изменении Общей информации работника
set_flash_message('success', 'Профиль успешно обновлен');


# Перенаправляем на страницу ввода Логин/пароля
redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/page_profile.php?id=' . $id);
