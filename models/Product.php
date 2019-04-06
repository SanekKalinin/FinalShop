<?php

class Product
{

    const SHOW_BY_DEFAULT = 10; // потом запихну в конфиг

    /**
     * получаем массив товаров с пометкой new
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {
        $count = intval($count);  // советуют, но можно и убрать, если задам в конфиге
        $db = Db::сonnection();
        $productsList = array();

        $result = $db->query('SELECT id, name, price, image, is_new FROM product '
                . 'WHERE status = "1"'
                . 'ORDER BY id DESC '                
                . 'LIMIT ' . $count);

        $i = 0;
        while ($row = $result->fetch()) {  // перейти на ПДО
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['image'] = $row['image'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }

        return $productsList;
    }
    
    /**
     * получаем массив товаров по категории
     */
    public static function getProductsListByCategory($categoryId = false)
    {
        if ($categoryId) {

            $db = Db::сonnection();            
            $products = array();
            $result = $db->query("SELECT id, name, price, image, is_new FROM product "
                    . "WHERE status = '1' AND category_id = '$categoryId' "
                    . "ORDER BY id DESC "                
                    . "LIMIT ".self::SHOW_BY_DEFAULT);

            $i = 0;
            while ($row = $result->fetch()) {   // перейти на PDO
                $products[$i]['id'] = $row['id'];
                $products[$i]['name'] = $row['name'];
                $products[$i]['image'] = $row['image'];
                $products[$i]['price'] = $row['price'];
                $products[$i]['is_new'] = $row['is_new'];
                $i++;
            }

            return $products;       
        }
    }
    
    
    /**
     * получаем массив товаров по ИД
     */
    public static function getProductById($id)
    {
        $id = intval($id);  // тоже можно убрать, советуют проверять

        if ($id) {                        
            $db = Db::сonnection();
            
            $result = $db->query('SELECT * FROM product WHERE id=' . $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            
            return $result->fetch();
        }
    }
    
    /**
     *получаем рекомендуемые товары
     */
    public static function getRecommendedProducts()
    {
        $db = Db::сonnection();

        $productsList = array();

        $result = $db->query('SELECT id, name, price, image, is_new FROM product '
                . 'WHERE status = "1" AND is_recommended = "1"'
                . 'ORDER BY id DESC ');

        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['image'] = $row['image'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }

        return $productsList;
    }

}