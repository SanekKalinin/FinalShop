<?php
abstract class Models {

    public function __construct () {
$connection = Db::getInstance();
$this->db=$connection->getConnection(); 
    }
}