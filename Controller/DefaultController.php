<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $user= '';
        $manager = UserManager::getInstance();
        if(isset($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        $allPlates = $manager->getPlates();
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
                            'user' => $user,
                            'dishes'=> $dishes,
                            'desserts'=> $desserts,
                            'drinks'=> $drinks,
                        ]);


    }
}
