<?php

namespace Controller;

use Model\CartManager;

class CartController extends BaseController
{
    public function editcartAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = CartManager::getInstance();
            if ($_POST["kind"] == "add") {
                if (is_numeric($_POST['productId'])) {
                    $manager->addProduct($_POST['productId']);
                }
            }
            elseif ($_POST["kind"] == "remove") {
                if (is_numeric($_POST['productId'])) {
                    $manager->removeProduct($_POST['productId']);
                }
            }
            elseif ($_POST["kind"] == "removeone") {
                if (is_numeric($_POST['productId'])) {
                    $_SESSION['cart'][$_POST['productId']]['quantity']--;
                    if($_SESSION['cart'][$_POST['productId']]['quantity'] <= 0) {
                        $manager->removeProduct($_POST['productId']);
                    }
                }
            }
            elseif ($_POST["kind"] == "addone") {
                if (is_numeric($_POST['productId'])) {
                    $_SESSION['cart'][$_POST['productId']]['quantity']++;
                }
            }
        }
    }

    public function viewcartAction() {
        $manager = CartManager::getInstance();
        $total = $manager->totalCart();
        echo $this->renderView('cart.html.twig', [
            'SessionEmail' => $_SESSION['email'],
            'CartElements' => $_SESSION['cart'],
            'Total' => $total,
        ]);
    }

    public function refreshcartAction() {
        $manager = CartManager::getInstance();
        $total = $manager->totalCart();
        echo $this->renderView('cartAJAX.html.twig', [
            'SessionEmail' => $_SESSION['email'],
            'CartElements' => $_SESSION['cart'],
            'Total' => $total,
        ]);
    }
}
