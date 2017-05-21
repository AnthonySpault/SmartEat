<?php
namespace Model;

use Model\UserManager;
use Model\CartManager;

class OrderManager
{
    private $DBManager;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new OrderManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }
    public function getAllOrders(){
        $data = $this->DBManager->findAll("SELECT * FROM orders ORDER BY orderdate DESC");
        return $data;
    }

    public function getOrderByUserId($id){
        $data = $this->DBManager->findAllSecure("SELECT * FROM orders WHERE userid = :id",
            ['id' => $id]);
        return $data;
    }

    public function getShippingByOrderId($id){
        $data = $this->DBManager->findOneSecure("SELECT * FROM addresses WHERE id = :id",
            ['id' => $id]);
        return $data;
    }

    public function checkAddresses($data) {
        if (!is_numeric($data['billing']) || !is_numeric($data['shipping']))
            return 'Action interdite';
        $UserManager = UserManager::getInstance();
        $billing = $UserManager->getAddressById($data['billing']);
        if ($billing['userid'] != $_SESSION['user_id'])
            return 'L\'adresse de facturation ne vous appartient pas.';
        $shipping = $UserManager->getAddressById($data['shipping']);
        if ($shipping['userid'] != $_SESSION['user_id'])
            return 'L\'adresse d\'expedition ne vous appartient pas.';
        $_SESSION['order']['data']['billing'] = $data['billing'];
        $_SESSION['order']['data']['shipping'] = $data['shipping'];
        $_SESSION['order']['step'] = 2;
        return true;

    }

    public function validatePayment($total) {
        $UserManager = UserManager::getInstance();
        $user = $UserManager->getUserById($_SESSION['user_id']);
        $userInsert['id'] = $_SESSION['user_id'];
        $userInsert['points'] = $user['points'];
        $userInsert['points'] += floor($total);
        $this->DBManager->doRequestSecure("UPDATE users SET points = :points WHERE id = :id", $userInsert);
        $orderInsert['userid'] = $_SESSION['user_id'];
        $orderInsert['products'] = '';
        foreach ($_SESSION['cart'] as $key => $value) {
            $orderInsert['products'] .= $_SESSION['cart'][$key]['quantity'].'x '.$_SESSION['cart'][$key]['name'].';';
        }
        $orderInsert['products'] = substr($orderInsert['products'], 0, -1);
        $orderInsert['total'] = $total + 2;
        $orderInsert['tips'] = $_SESSION['tips'];
        $orderInsert['billingaddress'] = $_SESSION['order']['data']['billing'];
        $orderInsert['shippingaddress'] = $_SESSION['order']['data']['shipping'];
        $this->DBManager->insert('orders', $orderInsert);
        unset($_SESSION['order']);
        unset($_SESSION['cart']);
        unset($_SESSION['cartmealreduc']);
        unset($_SESSION['tips']);
    }
}
