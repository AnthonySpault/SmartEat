<?php

namespace Controller;

use Model\CartManager;
use Model\UserManager;

class CartController extends BaseController
{
    public function editcartAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = CartManager::getInstance();
            $UserManager = UserManager::getInstance();
            if (isset($_SESSION['user_id'])){
                $user = $UserManager->getUserById($_SESSION['user_id']);
            }
            if ($_POST["kind"] == "add") {
                if (is_numeric($_POST['productId'])) {
                    $manager->addProduct($_POST['productId']);
                }
            }
            elseif ($_POST["kind"] == "remove") {
                if (is_numeric($_POST['productId'])) {
                    $manager->removeProduct($_POST['productId']);
                }
                $total = $manager->totalCart();
                if (isset($_SESSION['email'])) {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'SessionEmail' => $_SESSION['email'],
                        'user'=>$user,
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
                else {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
            }
            elseif ($_POST["kind"] == "removeone") {
                if (is_numeric($_POST['productId'])) {
                    $_SESSION['cart'][$_POST['productId']]['quantity']--;
                    if($_SESSION['cart'][$_POST['productId']]['quantity'] <= 0) {
                        $manager->removeProduct($_POST['productId']);
                    }
                }
                $total = $manager->totalCart();
                if (isset($_SESSION['email'])) {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'SessionEmail' => $_SESSION['email'],
                        'user'=>$user,
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
                else {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
            }
            elseif ($_POST["kind"] == "addone") {
                if (is_numeric($_POST['productId'])) {
                    $_SESSION['cart'][$_POST['productId']]['quantity']++;
                }
                $total = $manager->totalCart();
                if (isset($_SESSION['email'])) {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'SessionEmail' => $_SESSION['email'],
                        'user'=>$user,
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
                else {
                    echo $this->renderView('cartAJAX.html.twig', [
                        'CartElements' => $_SESSION['cart'],
                        'MealReductions' => $_SESSION['cartmealreduc'],
                        'Total' => $total,
                    ]);
                }
            }
        }
    }

    public function viewcartAction() {
        $manager = CartManager::getInstance();
        $total = $manager->totalCart();
        $UserManager = UserManager::getInstance();
        if (isset($_SESSION['user_id'])){
            $user = $UserManager->getUserById($_SESSION['user_id']);
        }
        if (isset($_SESSION['email'])) {
            echo $this->renderView('cart.html.twig', [
                'SessionEmail' => $_SESSION['email'],
                'user'=>$user,
                'CartElements' => $_SESSION['cart'],
                'MealReductions' => $_SESSION['cartmealreduc'],
                'Total' => $total,
            ]);
        }
        else {
            echo $this->renderView('cart.html.twig', [
                'CartElements' => $_SESSION['cart'],
                'MealReductions' => $_SESSION['cartmealreduc'],
                'Total' => $total,
            ]);
        }
    }
}
