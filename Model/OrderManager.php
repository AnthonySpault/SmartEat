<?php
namespace Model;

class OrderManager
{
    private $OrderManager;
    private static $instance = null;

    private function __construct()
    {
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