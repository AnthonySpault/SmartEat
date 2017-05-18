<?php

namespace Model;

use Model\ContentManager;

class CartManager
{
    private $DBManager;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new CartManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
        $this->ContentManager = ContentManager::getInstance();
    }

    public function addProduct($id) {
        $manager = ContentManager::getInstance();
        $product = $manager->getOnePlates($id);
        if (isset($_SESSION['cart'][$product['id']])) {
            $_SESSION['cart'][$product['id']]['quantity']++;
            exit(0);
        }
        $_SESSION['cart'][$product['id']]['name'] = $product['name'];
        $_SESSION['cart'][$product['id']]['price'] = $product['price'];
        $_SESSION['cart'][$product['id']]['img'] = $product['image'];
        $_SESSION['cart'][$product['id']]['quantity'] = 1;
    }

    public function removeProduct($id) {
        unset($_SESSION['cart'][$id]);
    }

    public function totalCart() {
        foreach ($_SESSION['cart'] as $key => $value) {
            $price = $_SESSION['cart'][$key]['quantity'] * $_SESSION['cart'][$key]['price'];
            $total += $price;
        }
        return $total;
    }
}
