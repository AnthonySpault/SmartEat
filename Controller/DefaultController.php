<?php

namespace Controller;

use Model\ContentManager;
use Model\UserManager;

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
}
