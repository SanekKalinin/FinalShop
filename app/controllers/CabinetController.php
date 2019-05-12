<?php

/**
 * Контроллер CabinetController
 * Кабинет пользователя
 */
class CabinetController
{
    //подключаем класс User
    protected $user= false;

    public function __construct() {
      
      $this->user = new User();
  
    }

    /**
     * Action для страницы "Кабинет пользователя"
     */
    public function actionIndex()
    {
        // Получаем идентификатор пользователя из сессии
        $userId =$this->user->checkLogged();

        // Получаем информацию о пользователе из БД
        $user =$this->user->getUserById($userId);

        // Подключаем вид
        require_once(BASEPATH . '/app/views/cabinet/index.php');
        return true;
    }

    /**
     * Action для страницы "Редактирование данных пользователя"
     */
    public function actionEdit()
    {
        // Получаем идентификатор пользователя из сессии
        $userId =$this->user->checkLogged();

        // Получаем информацию о пользователе из БД
        $user =$this->user->getUserById($userId);

        // Заполняем переменные для полей формы
        $userName = $user['username'];

        // Флаг результата
        $result = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования
            $userName = $_POST['username'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            // Валидируем значения
            if (!$this->user->checkName($userName)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!$this->user->checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            if ($errors == false) {
                // Если ошибок нет, сохраняет изменения профиля
                $result =$this->user->edit($userId, $userName, $password);
            }
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/cabinet/edit.php');
        return true;
    }

}
