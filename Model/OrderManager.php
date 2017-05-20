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
        $_SESSION['order']['status'] = "complete";
        $orderInsert['userid'] = $_SESSION['user_id'];
        $orderInsert['products'] = '';
        foreach ($_SESSION['cart'] as $key => $value) {
            $orderInsert['products'] .= $key.',';
        }
        $orderInsert['total'] = $total;
        $orderInsert['billing'] = $_SESSION['order']['data']['billing'];
        $orderInsert['shipping'] = $_SESSION['order']['data']['shipping'];
        $this->DBManager->insert('orders', $orderInsert);
    }
}
