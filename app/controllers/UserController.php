<?php

/**
 * Контроллер UserController
 */
class UserController
{
    // подключаем класс User
  protected $user= false;


  public function __construct() {
    
    $this->user = new User();

  }
    /**
     * Action для страницы "Регистрация"
     */

    public function actionRegister()
    {
       // $user = $this->getModel('user');  так надо реализовать
        // $model = User::model();  так в каком-то фрэймворке
       // $user= new User();
        // Переменные для формы
        $firstName=false;
        $lastName=false;
        $userName = false;
        $email = false;
        $password = false;
        $result = false;
        

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена 
            // Получаем данные из формы
            $userName = $_POST['userName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!$this->user->checkName($userName)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!$this->user->checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!$this->user->checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 8-ти символов';
            }
            if ($this->user->checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            
            if ($errors == false) {
                // Если ошибок нет
                // Регистрируем пользователя
                $result = $this->user->register($firstName, $lastName, $userName, $email, $password);
            }
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/user/register.php');
        return true;
    }
    // action для проверки регистрации
    public function actionRegisterValidation() {
    if (isset($_GET['reg_tocken'])) {
       // $userID=$this->user->;
        //registerValidation($_GET['reg_tocken'];$userID);
    }
    }
    /**
     * Action для страницы "Вход на сайт"
     */
    public function actionLogin()
    {
        // подключаем модель User
       // $user= new User();
        // Переменные для формы
        $email = false;
        $password = false;
        
        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена 
            // Получаем данные из формы
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!$this->user->checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!$this->user->checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 8-ти символов';
            }

            // Проверяем существует ли пользователь
            $userId = $this->user->userValidation($email, $password);

            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт';
            } else {
                // Если данные правильные, запоминаем пользователя (сессия)
                $this->user->auth($userId);

                // Перенаправляем пользователя в закрытую часть - кабинет 
                header("Location: /cabinet");
            }
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/user/login.php');
        return true;
    }

    /**
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout()
    {
        // Стартуем сессию
        session_start();
        
        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);
        
        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }

}
