<?php

/**
 * Класс User - модель для работы с пользователями
 */
class User
{

    /**
     * Регистрация пользователя 
     * @param string $userName <p>Имя</p>
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function register($userName, $email, $password)
    {
        // Соединение с БД
        $db = Db::getConnection();
        $salt=random_int(0, PHP_INT_MAX);
        $hashPass=md5($userName.$password.$salt);
        // Текст запроса к БД
        $sql = 'INSERT INTO user (username, email, salt, hash) '
                . 'VALUES (:userName, :email, :salt, :hashPass)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':salt', $salt, PDO::PARAM_STR);
        $result->bindParam(':hashPass', $hashPass, PDO::PARAM_STR);
        
        try {
        return $result->execute(); }
        catch(PDOException $e) {  
            echo $e->getMessage();  
                    }
    }

    /**
     * Редактирование данных пользователя
     * @param integer $id <p>id пользователя</p>
     * @param string $userName <p>Имя</p>
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function edit($id, $userName, $password)
    {
        // Соединение с БД
        $db = Db::getConnection();
        $salt=random_int(0, PHP_INT_MAX);
        $hashPass=md5($userName.$password.$salt);

        // Текст запроса к БД
        $sql = "UPDATE user 
            SET username = :userName, salt = :salt; hash = :hashPass 
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':salt', $salt, PDO::PARAM_STR);
        $result->bindParam(':hashPass', $hashPass, PDO::PARAM_STR);
        try {
            return $result->execute(); }
            catch(PDOException $e) {  
                echo $e->getMessage();  
                        }
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return mixed : integer user id or false
     */
    public static function userValidation($email, $password)
    {
        
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE email = :email';
        
        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);

        $result->bindParam(':email', $email, PDO::PARAM_STR);
        
        try {
            // делаем только ассоциативный массив
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
             }
            catch(PDOException $e) {  
                echo $e->getMessage();  
                        }

        // Обращаемся к записи
        $user = $result->fetch();
        
        if (md5($user['username'].$password.$user['salt'])==$user['hash']) {
            // Если хэши совпадают, возвращаем id пользователя
            return $user['id'];
        }
        return false;
    }

    /**
     * Запоминаем пользователя
     * @param integer $userId <p>id пользователя</p>
     */
    public static function auth($userId)
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $userId;
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
     * @return string <p>Идентификатор пользователя</p>
     */
    public static function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header("Location: /user/login");
    }

    /**
     * Проверяет является ли пользователь гостем
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет имя: не меньше, чем 2 символа
     * @param string $userName <p>Имя</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkName($userName)
    {
        if (strlen($userName) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет телефон: не меньше, чем 10 символов
     * @param string $phone <p>Телефон</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 8 символов
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 8) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет не занят ли email другим пользователем
     * @param type $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmailExists($email)
    {
        // Соединение с БД        
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        try {
            return $result->execute(); }
            catch(PDOException $e) {  
                echo $e->getMessage();  
                        }

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Возвращает пользователя с указанным id
     * @param integer $id <p>id пользователя</p>
     * @return array <p>Массив с информацией о пользователе</p>
     */
    public static function getUserById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

}
