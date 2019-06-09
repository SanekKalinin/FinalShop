<?php

/**
 * Класс User - модель для работы с пользователями
 */
class User extends Models
{

    // Регистрация пользователя 
     
    public function register($userName, $email,$firstName,$lastName,$password)
    {
        // Соединение с БД
       // $db = Db::getConnection();
        $salt=random_int(0, PHP_INT_MAX);
        $hashPass=md5($userName.$password.$salt);
        $reg_tocken=md5(time());
        // Текст запроса к БД
        $sql = 'INSERT INTO user (username, firstName, lastName, email, reg_tocken, salt, hash) '
                . 'VALUES (:userName,:firstName, :lastName, :email,:reg_tocken, :salt, :hashPass)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $result->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':reg_tocken', $reg_tocken, PDO::PARAM_STR);
        $result->bindParam(':salt', $salt, PDO::PARAM_STR);
        $result->bindParam(':hashPass', $hashPass, PDO::PARAM_STR);
        //mail($email,'Завершение регистрации','Для завершения регистрации перейдите по ссылке http://shop/user='.$reg_tocken);
        try {
        return $result->execute(); }
        catch(PDOException $e) {  
            echo $e->getMessage();  
                    }
    }
    // подтверждение регистрации
    public function registerValidation($reg_tocken,$userID) {
        // $db = Db::getConnection();
        // готовим запрос
        $sql = 'SELECT :reg_tocken FROM user WHERE userID = :userID';
        
        // Получение результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':reg_tocken', $reg_tocken, PDO::PARAM_STR);
        $result->bindParam(':userID', $userID, PDO::PARAM_STR);
        try {
            $result->execute();}
            catch(PDOException $e) {  
                echo $e->getMessage();  
                        }
            $user = $result->fetch();
            if ($reg_tocken==$user['reg_tocken']) {
            return true; } 
            else return false;
            

    }
    /**
     * Редактирование данных пользователя
     
     */
    public function edit($id, $userName, $password)
    {
        // Соединение с БД
        // $db = Db::getConnection();
        $salt=random_int(0, PHP_INT_MAX);
        $hashPass=md5($userName.$password.$salt);

        // Текст запроса к БД
        $sql = "UPDATE user 
            SET username = :userName, salt = :salt; hash = :hashPass 
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
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
   
     */
    public function userValidation($email, $password)
    {
        
        // Соединение с БД
        //$db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE email = :email';
        
        // Получение результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);

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
       // $user['tocken']=md5(time());
        if (md5($user['username'].$password.$user['salt'])==$user['hash']) {
             //Если хэши совпадают, возвращаем id пользователя и создаем токен для куки
          setcookie("userTocken",$user['tocken'],time()+60*60*24,$httponly=true);
                                     
          return $user['id'];
        }
        return false;
    }

  // Запоминаем пользователя
    
    public function auth($userID)
    {
             
         // Соединение с БД
         // $db = Db::getConnection();
         /*
         $user = $this->db->select('SELECT username, email,firstName, lastName, tocken FROM user WHERE userID = :userID', [
             ':userId' => $user_id
         ]);
         */
         // Текст запроса к БД
         $sql = 'SELECT username, email,firstName, lastName, tocken FROM user WHERE id = :userID' ;
 
         // Получение и возврат результатов. Используется подготовленный запрос
         
         $result = $this->db->prepare($sql);
         $result->bindParam(':userID', $userID, PDO::PARAM_STR);
         $result->setFetchMode(PDO::FETCH_ASSOC);
         
         try {
            $result->execute();
            }
         catch(PDOException $e) {  
             echo $e->getMessage();  
                     }
        // Записываем идентификатор пользователя в сессию
         
        return $_SESSION = $result->fetch();
          
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
          */
    public function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя, браузер не закрывался
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        }
        // если браузер закрывался, проверяем куку
        if (isset($_COOKIE["userTocken"])) {
           // Соединение с БД
        // $db = Db::getConnection();
         
         // Текст запроса к БД
         $sql = 'SELECT username, email,firstName, lastName, tocken FROM user WHERE tocken = :tocken' ;
 
         // Получение и возврат результатов. Используется подготовленный запрос
         $result = $this->db->prepare($sql);
         $result->bindParam(':tocken', $_COOKIE["userTocken"], PDO::PARAM_STR);
         
         try {
            $result->execute();
            $_SESSION = $result->fetch();
            return $_SESSION['username'];}
         catch(PDOException $e) {  
             echo $e->getMessage();   
        }

        header("Location: /user/login");
    }
        }

    // Проверяет является ли пользователь гостем
     
    public static function isGuest()
    {
        if (isset($_SESSION['username'])) {
            return false;
        }
        return true;
    }

   // Проверяет имя: не меньше, чем 2 символа
     
    public function checkName($userName)
    {
        if (strlen($userName) >= 2) {
            return true;
        }
        return false;
    }

    // Проверяет телефон: не меньше, чем 10 символов
    
    public  function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

   // Проверяет имя: не меньше, чем 8 символов
     
    public  function checkPassword($password)
    {
        if (strlen($password) >= 8) {
            return true;
        }
        return false;
    }

    // Проверяет email
     
    public  function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    // Проверяет не занят ли email другим пользователем
     
    public  function checkEmailExists($email)
    {
        // Соединение с БД        
       // $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        // Получение результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        try {
             $result->execute(); }
            catch(PDOException $e) {  
                echo $e->getMessage();  
                        }

        if ($result->fetchColumn()) {
            
            return true;
        }
        return false;
    }

    // Возвращает пользователя с указанным id
  
    public  function getUserById($id)
    {
        // Соединение с БД
      // $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде ассоциативного массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

}
