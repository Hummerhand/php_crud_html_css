<?php

session_start();

# Получение(возвращяет найденного) работника по эл.аресу
function get_employee_by_email($email)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $sql = "SELECT * FROM employees WHERE email=:email";

    $statement = $pdo->prepare($sql);

    $statement->execute(['email' => $email]);

    $employee = $statement->fetch(PDO::FETCH_ASSOC);

    return $employee;
}

# Подготовить флеш-сообщение
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

# Перенаправить по указанному адресу
function redirect_to($path)
{
    header("Location: {$path}");
    exit;
}

# Зарегистрировать нового полльзовтателя в БД (По почте, паролю и роли)
function registration_employee($email, $password)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $role = 'user';

    $sql = "INSERT INTO employees (email, password, role) VALUES (:email, :password, :role)";

    $statement = $pdo->prepare($sql);

    $statement->execute(
        [
            'email'    => $email,
            'password'  => $hashed_password,
            'role'      => $role
        ]
    );

    return $pdo->lastInsertId();
}

# Вывести флеш-сообщение
function display_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}

# Проверяет совпадают ли введенный Логин и пароль с данными из БД (Авторизация)
function login($email, $password)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $sql = "SELECT * FROM employees WHERE email=:email";

    $statement = $pdo->prepare($sql);

    $statement->execute(['email' => $email]);

    $employee = $statement->fetch(PDO::FETCH_ASSOC);

    if (empty($employee) || !password_verify($password, $employee['password'])) {

        return false;
    }

    $_SESSION['employee'] = [
        'id'    => $employee['id'],
        'email' => $employee['email'],
        'role'  => $employee['role']
    ];

    /*
        $_SESSION['employee']['id'] = $employee['id'];
        $_SESSION['employee']['email'] = $employee['email'];
        $_SESSION['employee']['role'] = $employee['role'];
        */
    return true;
}

# Проверяет имеется ли в Сессии работник (т.е. авторизован ли работник)
function is_logged_in()
{
    if (isset($_SESSION['employee'])) {

        return true;
    }

    return false;
}

# Проверяет не авторизован ли работник
function is_not_logged_in()
{
    return !is_logged_in();
}

# Получает текущего работника, которой прошел авторизацию
function get_current_employee()
{
    if (is_logged_in()) {

        return $_SESSION['employee'];
    }

    return false;
}

# Проверяет является ли пользователь Админом
function is_admin($employee)
{
    if (is_logged_in()) {

        if ($employee['role'] === 'admin') {

            return true;
        }

        return false;
    }
}

# Сравнивает пользователя из БД с текущим авторизованным пользователем
function is_equal($employee, $current_employee)
{
    if ($employee['id'] === $current_employee['id']) {

        return true;
    }

    return false;
}

# Возвращяет список работников из БД
function get_employees()
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $sql = "SELECT * FROM employees";

    $statement = $pdo->prepare($sql);

    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

# Добавить + Зарегистрировать нового полльзовтателя в БД (По всем полям формы)
function add_new_employee($employeeName, $jobTitle, $phone, $address, $email, $password, $status, $image)
{
    # Подключение к базе данных    
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    # Получение информации о загруженной картинке
    if (!empty($image)) {

        $imageName = $image['name'];
        $imageSize = $image['size'];

        # Генерация уникального имени файла
        $uniqueName = uniqid() . '_' . $imageName;

        # Куда и как будем сохранять картинку (папка на сервере)
        $imagesPath = __DIR__ . '/avatars/' . $uniqueName;

        # Проверка размера файла (не более 5 Мб)
        $maxFileSize = 5 * 1024 * 1024;             # 5 Мб в байтах
        if ($maxFileSize < $imageSize) {

            set_flash_message('danger', ' Файл слишком большой. Максимальный размер файла: 5 МБ');

            redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/create_user.php');
        }

        # Перемещение файла(картинки)
        if (!move_uploaded_file($image['tmp_name'], $imagesPath)) {

            set_flash_message('danger', ' Ошибка при загрузке файла. Попробуйте еще раз');

            redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/create_user.php');
        }

        # Хешируется введенный пароль пользователя
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        # Устанавливается роль "user" по умолчанию новому пользователю
        $role = 'user';

        # Вставка информации в базу данных
        $sql = "INSERT INTO employees (employee_name, job_title, phone, address, email, password, status, role, image) 
            VALUES (:employee_name, :job_title, :phone, :address, :email, :password, :status, :role, :image)";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            'employee_name' => $employeeName,
            'job_title'     => $jobTitle,
            'phone'         => $phone,
            'address'       => $address,
            'email'         => $email,
            'password'      => $hashed_password,
            'status'        => $status,
            'role'          => $role,
            'image'         => $uniqueName
        ]);
    }
}

# Получение(возвращяет найденного) работника по id
function get_employee_by_id($id)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $sql = "SELECT * FROM employees WHERE id=:id";

    $statement = $pdo->prepare($sql);

    $statement->execute(['id' => $id]);

    $employee = $statement->fetch(PDO::FETCH_ASSOC);

    return $employee;
}

# Изменение полей работника в БД (Общая информация)
function update_employee_by_general_inf($id, $employeeName, $jobTitle, $phone, $address)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    if (!empty($id) && !empty($employeeName) && !empty($jobTitle) && !empty($phone) && !empty($address)) {

        $sql = "UPDATE employees SET employee_name=:employee_name, job_title=:job_title, phone=:phone, address=:address 
        WHERE id=:id";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            'id'            => $id,
            'employee_name' => $employeeName,
            'job_title'     => $jobTitle,
            'phone'         => $phone,
            'address'       => $address
        ]);
    }
}

# Изменение полей работника в БД (Логин и пароль)
function update_employee_by_security_inf($id, $email, $password, $confirm_password)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    # Получаем работника с указанным в поле email
    $employee = get_employee_by_email($email);


    # Если такой email занят, перенаправляем на эту же страницу с id
    if (!empty($employee) and $employee['id'] !== $id) {

        set_flash_message('danger', ' Введенная учетная запись уже существует');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/security.php?id=' . $id);
    }


    # Если введенные пароли не совпадают перенаправляем на эту же страницу с id
    if ($password !== $confirm_password) {

        set_flash_message('danger', ' Пароли не совпадают');

        redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/security.php?id=' . $id);
    }

    # Хешируем новый пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE employees SET email=:email, password=:password WHERE id=:id";

    $statement = $pdo->prepare($sql);

    $statement->execute([
        'id'            => $id,
        'email'         => $email,
        'password'      => $hashed_password
    ]);
}

# Изменение статуса работника в БД (Общая информация)
function update_employee_by_status($id, $status)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    if (!empty($id) && !empty($status)) {

        $sql = "UPDATE employees SET status=:status WHERE id=:id";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            'id'            => $id,
            'status'        => $status
        ]);
    }
}

# Изменение аватарки работника в БД (Общая информация)
function update_employee_image($id, $image)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    # Получение информации о загруженной картинке
    if (!empty($image)) {

        $imageName = $image['name'];
        $imageSize = $image['size'];

        # Генерация уникального имени файла
        $uniqueName = uniqid() . '_' . $imageName;

        # Куда и как будем сохранять картинку (папка на сервере)
        $imagesPath = __DIR__ . '/avatars/' . $uniqueName;

        # Проверка размера файла (не более 5 Мб)
        $maxFileSize = 5 * 1024 * 1024;             # 5 Мб в байтах
        if ($maxFileSize < $imageSize) {

            set_flash_message('danger', ' Файл слишком большой. Максимальный размер файла: 5 МБ');

            redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/media.php?id=' . $id);
        }

        # Перемещение файла(картинки)
        if (!move_uploaded_file($image['tmp_name'], $imagesPath)) {

            set_flash_message('danger', ' Ошибка при загрузке файла. Попробуйте еще раз');

            redirect_to('http://project/Обучение_PHP/Project_1/Project%20layout/media.php?id=' . $id);
        }

        $sql = "UPDATE employees SET image=:image WHERE id=:id";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            'id'            => $id,
            'image'         => $uniqueName
        ]);
    }
}

# Удаление работника по id
function delete_employee_by_id($id)
{
    $pdo = new PDO("mysql:host=127.0.0.1:3300;dbname=my_database;", "root", "");

    $sql = "DELETE FROM employees WHERE id=:id";

    $statement = $pdo->prepare($sql);

    $statement->execute(['id' => $id]);
}
