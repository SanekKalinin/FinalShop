<?php

/**
 * Класс Db
 * Компонент для работы с базой данных
 * подробно тут https://habr.com/en/post/137664/
 * Увидеть список доступных драйверов можно так: print_r(PDO::getAvailableDrivers());
 */
class Db
{

    /**
     * Устанавливает соединение с базой данных
     * @return \PDO <p>Объект класса PDO для работы с БД</p>
     */
    public static function getConnection()
    {
        // Получаем параметры подключения из файла
        $config = include (BASEPATH . '/config/db_config.php');
        
        // Устанавливаем соединение
        try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        $db = new PDO($dsn, $config['user'], $config['password']);
        // смотрим ошибки запросов, закоментировать при переносе на хост
         $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); }
    catch(PDOException $e) {  
        echo $e->getMessage();  
        // добавить позже вывод проблем в файл
        // echo "Хьюстон, у нас проблемы.";  
    // file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); 
                }
        // Задаем кодировку
        $db->exec("set names utf8");

        return $db;
    }

}
