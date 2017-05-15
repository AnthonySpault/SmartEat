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

            echo $this->renderView('home.html.twig',
                ['user' => $user,'allPlates'=> $allPlates]);


    }
}
