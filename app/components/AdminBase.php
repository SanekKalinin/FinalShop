<?php

/**
 * Абстрактный класс AdminBase содержит общую логику для контроллеров, которые 
 * используются в панели администратора
 */
abstract class AdminBase 
{
    //подключаем класс User
    protected $user= false;

    public function __construct() {
      
      $this->user = new User();
  
    }
    /**
     * Метод, который проверяет пользователя на то, является ли он администратором
     * @return boolean
     */
    public function checkAdmin()
    {
        // Проверяем авторизирован ли пользователь. Если нет, он будет переадресован
        $userId =$this->user->checkLogged();

        // Получаем информацию о текущем пользователе
        $user =$this->user->getUserById($userId);

        // Если роль текущего пользователя "admin", пускаем его в админпанель
        if ($user['role'] == 'admin') {
            return true;
        }

        // Иначе завершаем работу с сообщением об закрытом доступе
        die('Access denied');
    }

}
