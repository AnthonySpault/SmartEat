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
    public function checkBasket($data){

        if(!is_int($data['dish'])){
            return "Plat non valide";
        }
        if(!is_int($data['dessert'])){
            return "Dessert non valide";
        }

        if(!is_int($data['drinks'])){
            return "Boisson non valide";
        }
        return true;


    }
    public function basket($data){

        $insert['plateID'] = $data['dish'];
        $insert['dessertID'] = $data['dessert'];
        $insert['drinkID'] = $data['drinks'];
        $this->DBManager->insert('meal', $insert);
    }

}

?>