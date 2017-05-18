<?php
namespace Model;

class OrderManager
{
    private $OrderManager;
    private static $instance = null;
    private $DBManager;
    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
        $this->dbh = null;
    }


    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new OrderManager();
        return self::$instance;
    }
    

}

?>
