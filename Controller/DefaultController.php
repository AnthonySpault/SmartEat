<?php

namespace Controller;

use Model\ContentManager;
use Model\UserManager;
use Model\OrderManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $manager = ContentManager::getInstance();
        $allPlates = $manager->getCurrentPlates();
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
        echo $this->renderView('home.html.twig', [
                            'dishes'=> $dishes,
                            'desserts'=> $desserts,
                            'drinks'=> $drinks,
                        ]);
    }

    public function conceptAction() {
        echo $this->renderView('concept.html.twig');
    }

    public function partnersAction() {
        echo $this->renderView('partners.html.twig');
    }

    public function customizeAction() {
        $contentManager = ContentManager::getInstance();
        $orderManager = OrderManager::getInstance();
        $allPlates = $contentManager->getCurrentPlates();
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $check = $orderManager->checkBasket($_POST);
                if($check){
                    $orderManager->basket($_POST);
                    echo 'true';
                    exit(0);
                }else{
                    echo $check;
                    exit(0);
                }



        }

        echo $this->renderView('customize.html.twig', [
            'dishes'=> $dishes,
            'desserts'=> $desserts,
            'drinks'=> $drinks,
        ]);

    }
}
