<?php

namespace Controller;

use Model\CartManager;
use Model\ContentManager;

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
            if ($value['category'] == "dish") {
                $dishes[] = $value;
            }
            if ($value['category'] == "dessert") {
                $desserts[] = $value;
            }
            if ($value['category'] == "drink") {
                $drinks[] = $value;
            }
            if ($value['category'] == "extra") {
                $extras[] = $value;
            }
        }

        if (isset($_SESSION['email'])) {
            echo $this->renderView('home.html.twig', [
                'SessionEmail' => $_SESSION['email'],
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
                'extras' => $extras,
            ]);
        } else {
            echo $this->renderView('home.html.twig', [
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
                'extras' => $extras,
            ]);
        }

    }

    public function conceptAction()
    {
        if (isset($_SESSION['email'])) {
            echo $this->renderView('concept.html.twig', [
                'SessionEmail' => $_SESSION['email']
            ]);
        } else {
            echo $this->renderView('concept.html.twig');
        }

    }

    public function partnersAction()
    {
        if (isset($_SESSION['email'])) {
            echo $this->renderView('partners.html.twig', [
                'SessionEmail' => $_SESSION['email']
            ]);
        } else {
            echo $this->renderView('partners.html.twig');
        }
    }

    public function customizeAction()
    {
        $CartManager = CartManager::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $check = $CartManager->checkMeal($_POST);
            if ($check) {
                $CartManager->addMeal($_POST);
                echo 'true';
                exit(0);
            } else {
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
            if ($value['category'] == "dish") {
                $dishes[] = $value;
            }
            if ($value['category'] == "dessert") {
                $desserts[] = $value;
            }
            if ($value['category'] == "drink") {
                $drinks[] = $value;
            }
        }
        if (isset($_SESSION['email'])) {
            echo $this->renderView('customize.html.twig', [
                'SessionEmail' => $_SESSION['email'],
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
            ]);
        } else {
            echo $this->renderView('customize.html.twig', [
                'dishes' => $dishes,
                'desserts' => $desserts,
                'drinks' => $drinks,
            ]);
        }


    }
}
