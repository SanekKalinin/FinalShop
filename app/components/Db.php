<?php

/**
 * Компонент для работы с базой данных
 * подробно тут https://habr.com/en/post/137664/
 * Увидеть список доступных драйверов можно так: print_r(PDO::getAvailableDrivers());
 */
class Db
{
    private static $instance = null;
    private $db = null;
        /**
     * Устанавливает соединение с базой данных
   */
    private function __construct () {
         // Получаем параметры подключения из файла
         //  в нем позже прописать как параметр тип базы. Через case реализовать разные подключение
        $config = include (BASEPATH . '/app/config/db_config.php');
         // Устанавливаем соединение
         try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $this->db = new PDO($dsn, $config['user'], $config['password']);
            // смотрим ошибки запросов, закоментировать при переносе на хост
             $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); }
        catch(PDOException $e) {  
            echo $e->getMessage();  
            // добавить позже вывод проблем в файл
            // echo "проблемы.";  
        // file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); 
                    }

            // Задаем кодировку
            $this->db->exec("set names utf8");
            
          //return $this->db;
                }
				
    public static function getInstance() 
    {
        if (self::$instance != null) {
			return self::$instance;
		}
        return self::$instance = new self();          
    }
    // вычитал, что такой магический метод надо сделать пустым для избежания повторного соединения
    private function __clone() { }
    
// Get  connection
public function getConnection() {
    return $this->db;
}

}
