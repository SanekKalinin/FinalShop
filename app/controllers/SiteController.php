<?php

/**
 * Контроллер CartController
 */
class SiteController
{
//подключаем класс User
protected $user= false;

public function __construct() {
  
  $this->user = new User();
  $this->category = new Category();
  $this->product = new Product();

}
    /**
     * Action для главной страницы
     */
    public function actionIndex()
    {
        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Список последних товаров
        $latestProducts = $this->product->getLatestProducts(6);

        // Список товаров для слайдера
        $sliderProducts = $this->product->getRecommendedProducts();

        // Подключаем вид
        require_once(BASEPATH . '/app/views/site/index.php');
        return true;
    }

    /**
     * Action для страницы "Контакты"
     */
    public function actionContact()
    {

        // Переменные для формы
        $userEmail = false;
        $userText = false;
        $result = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена 
            // Получаем данные из формы
            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!$this->user->checkEmail($userEmail)) {
                $errors[] = 'Неправильный email';
            }

            if ($errors == false) {
                // Если ошибок нет
                // Отправляем письмо администратору 
                $adminEmail = 'xxxxx@mail.ru';
                $message = "Текст: {$userText}. От {$userEmail}";
                $subject = 'Тема письма';
                $result = mail($adminEmail, $subject, $message);
                $result = true;
            }
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/site/contact.php');
        return true;
    }
    
    /**
     * Action для страницы "О магазине"
     */
    public function actionAbout()
    {
        // Подключаем вид
        require_once(BASEPATH . '/app/views/site/about.php');
        return true;
    }

}
