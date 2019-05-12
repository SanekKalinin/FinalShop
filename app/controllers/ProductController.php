<?php

/**
 * Контроллер ProductController
 * Товар
 */
class ProductController
{
    public function __construct() {
          $this->product = new Product();
           $this->category = new Category();
            }
    /**
     * Action для страницы просмотра товара
     * $productId <p>id товара</p>
     */
    public function actionView($productId)
    {
        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Получаем инфомрацию о товаре
        $product = $this->product->getProductById($productId);

        // Подключаем вид
        require_once(BASEPATH . '/app/views/product/view.php');
        return true;
    }

}
