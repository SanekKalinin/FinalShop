<?php

/**
 * Класс Category - модель для работы с категориями товаров
 */
class Category extends Models
{

    /**
     * Возвращает массив категорий для списка на сайте
     */
    public function getCategoriesList()
    {
        // Соединение с БД
       // расширяем модель $db = Db::getConnection();
        // Запрос к БД
        $result = $this->db->query('SELECT id, name FROM category WHERE status = "1" ORDER BY sort_order, name ASC');

        // Получение и возврат результатов
        $i = 0;
        $categoryList = array();
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Возвращает массив категорий для списка в админпанели <br/>
     * (при этом в результат попадают и включенные и выключенные категории)
     */
    public function getCategoriesListAdmin()
    {
        // Соединение с БД
        //$db = Db::getConnection();

        // Запрос к БД
        $result = $this->db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');

        // Получение и возврат результатов
        $categoryList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        return $categoryList;
    }

    
     // Удаляет категорию с заданным id
     
    public function deleteCategoryById($id)
    {
        // Соединение с БД
        //$db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM category WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    // Редактирование категории с заданным id
     
    public function updateCategoryById($id, $userName, $sortOrder, $status)
    {
        // Соединение с БД
        //$db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE category
            SET 
                name = :userName, 
                sort_order = :sort_order, 
                status = :status
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

   // Возвращает категорию с указанным id
     
    public function getCategoryById($id)
    {
        // Соединение с БД
        //$db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM category WHERE id = :id';

        // Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполняем запрос
        $result->execute();

        // Возвращаем данные
        return $result->fetch();
    }

    /**
     * Возвращает текстое пояснение статуса для категории :<br/>
     * <i>0 - Скрыта, 1 - Отображается</i>
          */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Отображается';
                break;
            case '0':
                return 'Скрыта';
                break;
        }
    }

    // Добавляет новую категорию
  
    public function createCategory($userName, $sortOrder, $status)
    {
        // Соединение с БД
        // $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO category (name, sort_order, status) '
                . 'VALUES (:userName, :sort_order, :status)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->db->prepare($sql);
        $result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        return $result->execute();
    }

}
