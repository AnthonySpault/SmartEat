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
        $_SESSION['cart'][$product['id']]['category'] = $product['category'];
        $_SESSION['cart'][$product['id']]['price'] = $product['price'];
        $_SESSION['cart'][$product['id']]['img'] = $product['image'];
        $_SESSION['cart'][$product['id']]['quantity'] = 1;
    }

    public function removeProduct($id) {
        unset($_SESSION['cart'][$id]);
    }

    public function totalCart() {
        if(!empty($_SESSION['cart'])) {
            unset($_SESSION['cartmealreduc']);
            $dish = 0;
            $dessert = 0;
            $drink = 0;
            foreach ($_SESSION['cart'] as $key => $value) {
                $price = $_SESSION['cart'][$key]['quantity'] * $_SESSION['cart'][$key]['price'];
                $total += $price;
                if ($_SESSION['cart'][$key]['category'] == "dish") {
                    $dish .= $_SESSION['cart'][$key]['quantity'];
                }
                if ($_SESSION['cart'][$key]['category'] == "dessert") {
                    $dessert .= $_SESSION['cart'][$key]['quantity'];
                }
                if ($_SESSION['cart'][$key]['category'] == "drink") {
                    $drink .= $_SESSION['cart'][$key]['quantity'];
                }
            }
            $reduc = min($dish, $dessert, $drink);
            for ($i = 0; $i < $reduc; $i++) {
                $_SESSION['cartmealreduc'][$i] = 2;
                $total -= 2;
            }
            return $total;
        }
        else {
            return "0";
        }
    }

    public function checkMeal($data){

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
    public function addMeal($data) {
        $this->addProduct($data['dish']);
        $this->addProduct($data['dessert']);
        $this->addProduct($data['drinks']);
    }
}
