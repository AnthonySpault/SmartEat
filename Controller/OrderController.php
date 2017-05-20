<?php

namespace Controller;

use Model\CartManager;
use Model\UserManager;
use Model\OrderManager;

class OrderController extends BaseController
{
    public function orderAction() {
        if (isset($_SESSION['user_id'])) {
            if ($_GET['step'] == 1) {
                $_SESSION['order']['step'] = 1;
            } elseif ($_GET['step'] == 2) {
                if (isset($_SESSION['order']['data']['billing']))
                    $_SESSION['order']['step'] = 2;
                else
                    $_SESSION['order']['step'] = 1;
            } elseif ($_GET['step'] == 3) {
                if ($_SESSION['order']['status'] == "complete")
                    $_SESSION['order']['step'] = 3;
                elseif (isset($_SESSION['order']['data']['billing']))
                    $_SESSION['order']['step'] = 2;
                else
                    $_SESSION['order']['step'] = 1;
            }
            $CartManager = CartManager::getInstance();
            $UserManager = UserManager::getInstance();
            $total = $CartManager->totalCart();
            $addresses = $UserManager->getAddressByUserId($_SESSION['user_id']);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $OrderManager = OrderManager::getInstance();
                if ($_POST['kind'] == 'step1') {
                    $check = $OrderManager->checkAddresses($_POST);
                    if ($check === true) {
                        echo 'true';
                        exit(0);
                    }
                    else {
                        echo $check;
                        exit(0);
                    }
                }
                if ($_POST['kind'] == 'step2') {
                    $total = $CartManager->totalCart();
                    $OrderManager->validatePayment($total);
                    echo 'true';
                    exit(0);
                }
            }
            if (!isset($_SESSION['order']) || $_SESSION['order']['step'] == 1) {
                $_SESSION['order']['step'] = 1;
                echo $this->renderView('orderStep1.html.twig', [
                    'SessionEmail' => $_SESSION['email'],
                    'CartElements' => $_SESSION['cart'],
                    'MealReductions' => $_SESSION['cartmealreduc'],
                    'Total' => $total,
                    'addresses' => $addresses,
                ]);
            }
            elseif ($_SESSION['order']['step'] == 2) {
                echo $this->renderView('orderStep2.html.twig', [
                    'SessionEmail' => $_SESSION['email'],
                    'CartElements' => $_SESSION['cart'],
                    'MealReductions' => $_SESSION['cartmealreduc'],
                    'Total' => $total,
                ]);
            }
            elseif ($_SESSION['order']['step'] == 3) {
                $total = $CartManager->totalCart();
                $user = $UserManager->getUserById($_SESSION['user_id']);
                $billing = $UserManager->getAddressById($_SESSION['order']['data']['billing']);
                $shipping = $UserManager->getAddressById($_SESSION['order']['data']['shipping']);
                echo $this->renderView('orderStep3.html.twig', [
                    'SessionEmail' => $_SESSION['email'],
                    'user' => $user,
                    'billing' => $billing,
                    'shipping' => $shipping,
                    'CartElements' => $_SESSION['cart'],
                    'Total' => $total,
                ]);
            }
        }
        else {
            echo $this->renderView('loginregister.html.twig');
        }
    }

}
