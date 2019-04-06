<?php
// про PDO здесь https://habr.com/en/post/137664/
class Db
{
    
    public static function сonnection() {
        $config = include(ROOT . '/config/db_config.php');
        // print_r(PDO::getAvailableDrivers());  можно посмотреть, какие драйвера установлены   
        $dbh = "mysql:host={$config['host']};dbname={$config['dbname']}"; // здесь можно поменять тип базы, надо попробовать через конфиг   
try {
        $db = new PDO($dbh, $config['user'], $config['password'],[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $db->exec("set names utf8");  // в mysql без этого кракозябры
        return $db;
    }
    catch(PDOException $e) {  
        echo $e->getMessage();  
        }    

    }
}
