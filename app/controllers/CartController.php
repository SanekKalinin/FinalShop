<?php

/**
 * Контроллер CartController
 * Корзина
 */
class CartController
{

    public function __construct() {
        $this->product = new Product();
        $this->user = new User();
        $this->category = new Category();
        $this->cart = new Cart();
        $this->order = new Order();
          }
    /**
     * Action для добавления товара в корзину синхронным запросом<br/>
     * (для примера, не используется)
     * $id <p>id товара</p>
     */
    public function actionAdd($id)
    {
        // Добавляем товар в корзину
        $this->cart->addProduct($id);

        // Возвращаем пользователя на страницу с которой он пришел
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }

    /**
     * Action для добавления товара в корзину при помощи асинхронного запроса (ajax)
     * @param integer $id <p>id товара</p>
     */
    public function actionAddAjax($id)
    {
        // Добавляем товар в корзину и печатаем результат: количество товаров в корзине
        echo $this->cart->addProduct($id);
        return true;
    }
    
    /**
     * Action для добавления товара в корзину синхронным запросом
     * @param integer $id <p>id товара</p>
     */
    public function actionDelete($id)
    {
        // Удаляем заданный товар из корзины
        $this->cart->deleteProduct($id);

        // Возвращаем пользователя в корзину
        header("Location: /cart");
    }

    /**
     * Action для страницы "Корзина"
     */
    public function actionIndex()
    {
        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Получим идентификаторы и количество товаров в корзине
        $productsInCart = $this->cart->getProducts();

        if ($productsInCart) {
            // Если в корзине есть товары, получаем полную информацию о товарах для списка
            // Получаем массив только с идентификаторами товаров
            $productsIds = array_keys($productsInCart);

            // Получаем массив с полной информацией о необходимых товарах
            $products = $this->product->getProdustsByIds($productsIds);

            // Получаем общую стоимость товаров
            $totalPrice = $this->cart->getTotalPrice($products);
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/cart/index.php');
        return true;
    }

    /**
     * Action для страницы "Оформление покупки"
     */
    public function actionCheckout()
    {
        // Получием данные из корзины      
        $productsInCart = $this->cart->getProducts();

        // Если товаров нет, отправляем пользователи искать товары на главную
        if ($productsInCart == false) {
            header("Location: /");
        }

        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Находим общую стоимость
        $productsIds = array_keys($productsInCart);
        $products = $this->product->getProdustsByIds($productsIds);
        $totalPrice = $this->cart->getTotalPrice($products);

        // Количество товаров
        $totalQuantity = $this->cart->countItems();

        // Поля для формы
        $userName = false;
        $userPhone = false;
        $userComment = false;

        // Статус успешного оформления заказа
        $result = false;

        // Проверяем является ли пользователь гостем
        if (!$this->user->isGuest()) {
            // Если пользователь не гость
            // Получаем информацию о пользователе из БД
            $userId = $this->user->checkLogged();
            $user = $this->user->getUserById($userId);
            $userName = $user['username'];
        } else {
            // Если гость, поля формы останутся пустыми
            $userId = false;
        }

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!$this->user->checkName($userName)) {
                $errors[] = 'Неправильное имя';
            }
            if (!$this->user->checkPhone($userPhone)) {
                $errors[] = 'Неправильный телефон';
            }


            if ($errors == false) {
                // Если ошибок нет
                // Сохраняем заказ в базе данных
                $result = $this->order->save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // Если заказ успешно сохранен
                    // Оповещаем администратора о новом заказе по почте                
                    $adminEmail = 'mail';
                    $message = '<a href="shop">Список заказов</a>';
                    $subject = 'Новый заказ!';
                    mail($adminEmail, $subject, $message);

                    // Очищаем корзину
                    $this->cart->clear();
                }
            }
        }

        // Подключаем вид
        require_once(BASEPATH . '/app/views/cart/checkout.php');
        return true;
    }

}
