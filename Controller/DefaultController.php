<?php

namespace Controller;

use Model\ContentManager;
use Model\UserManager;
use Model\CartManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $ContentManager = ContentManager::getInstance();
        $allPlates = $ContentManager->getCurrentPlates();
        $dishes = [];
        $desserts = [];
        $drinks = [];
        $extras = [];
        foreach ($allPlates as $key => $value) {
            if($value['category'] == "dish") {
                $dishes[] = $value;
            }
            if($value['category'] == "dessert") {
                $desserts[] = $value;
            }
            if($value['category'] == "drink") {
                $drinks[] = $value;
            }
            if($value['category'] == "extra") {
                $extras[] = $value;
            }
        }
        if(isset($_SESSION['email'])){
            echo $this->renderView('home.html.twig', [
                'SessionEmail' => $_SESSION['email'],
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
                'extras' => $extras,
            ]);
        }else{
            echo $this->renderView('home.html.twig', [
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
                'extras' => $extras,
            ]);
        }
    }

    public function conceptAction() {
        echo $this->renderView('concept.html.twig', [
            'SessionEmail' => $_SESSION['email']
        ]);
    }

    public function partnersAction() {
        echo $this->renderView('partners.html.twig', [
            'SessionEmail' => $_SESSION['email']
        ]);
    }

    public function customizeAction() {
        $CartManager = CartManager::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $check = $CartManager->checkMeal($_POST);
            if($check) {
                $CartManager->addMeal($_POST);
                echo 'true';
                exit(0);
            }
            else {
                echo $check;
                exit(0);
            }
        }
        $ContentManager = ContentManager::getInstance();
        $allPlates = $ContentManager->getCurrentPlates();
        $dishes = [];
        $desserts = [];
        $drinks = [];
        foreach ($allPlates as $key => $value) {
            if($value['category'] == "dish") {
                $dishes[] = $value;
            }
            if($value['category'] == "dessert") {
                $desserts[] = $value;
            }
            if($value['category'] == "drink") {
                $drinks[] = $value;
            }
        }
        if(isset($_SESSION['email'])) {
            echo $this->renderView('customize.html.twig', [
                'SessionEmail' => $_SESSION['email'],
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
            ]);
        }else{
            echo $this->renderView('customize.html.twig', [
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
            ]);
        }

    }
}
