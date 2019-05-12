<?php

/**
 * Контроллер CatalogController
 * Каталог товаров
 */
class CatalogController
{
    public function __construct() {
        $this->product = new Product();
        $this->category = new Category();
          }
    /**
     * Action для страницы "Каталог товаров"
     */
    public function actionIndex()
    {
        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Список последних товаров
        $latestProducts = $this->product->getLatestProducts(12);

        // Подключаем вид
        require_once(BASEPATH . '/app/views/catalog/index.php');
        return true;
    }

    /**
     * Action для страницы "Категория товаров"
     */
    public function actionCategory($categoryId, $page = 1)
    {
        // Список категорий для левого меню
        $categories = $this->category->getCategoriesList();

        // Список товаров в категории
        $categoryProducts = $this->product->getProductsListByCategory($categoryId, $page);

        // Общее количетсво товаров (необходимо для постраничной навигации)
        $total = $this->product->getTotalProductsInCategory($categoryId);

        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        // Подключаем вид
        require_once(BASEPATH . '/app/views/catalog/category.php');
        return true;
    }

}
