<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if (empty($_SESSION['user_id'])) {
                $manager = UserManager::getInstance();
                $check = $manager->userCheckLogin($_POST);
                    if ($check === true)
                    {
                        $manager->userLogin($_POST['email']);
                        echo 'true';
                    }
                else {
                    echo "Votre email et/ou votre mot de passe sont incorrects";
                }
            }
            else {
               $this->redirect('profile');
            }
        }
    }

    public function logoutAction()
    {
        session_destroy();
         $this->redirect('profile');
    }

    public function registerAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if (empty($_SESSION['user_id'])) {
                $manager = UserManager::getInstance();
                $check = $manager->userCheckRegister($_POST);
                if ($check === true)
                {
                    $check = $manager->userCheckAddress($_POST);
                    if ($check === true) {
                        $manager->userRegister($_POST);
                        $manager->userAddressInsert($_POST);
                        echo "true";
                        exit(0);
                    }
                    else {
                        echo $check;
                        exit(0);
                    }
                }
                else {
                    echo $check;
                    exit(0);
                }
            }
            else {
                $this->redirect('profile');
            }
        }
        else {
            $this->redirect('profile');
        }
    }


    public function profileAction() {
        if (isset($_SESSION['user_id'])) {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['firstnameEditing'])){
                    $check  = $manager->userCheckFirstname($_POST);
                    if($check === true){
                        $manager->firstnameEdition($_POST);
                        echo 'true';
                        exit(0);
                    }else{
                        echo $check;
                        exit(0);
                    }
                }
                if(isset($_POST['usernameEditing'])) {
                    if ($manager->userCheckUsername($_POST)) {
                        $manager->usernameEdition($_POST);
                    }
                }
                if(isset($_POST['lastnameEditing'])){
                    $check =$manager->userCheckLastname($_POST);
                    if( $check === true ){
                        $manager->lastnameEdition($_POST);
                        echo 'true';
                        exit(0);
                    }else{
                        echo $check;
                        exit(0);
                    }
                }
                if(isset($_POST['emailEditing'])){
                    $check = $manager->userCheckEmail($_POST);
                   if($check === true){

                        $manager->emailEdition($_POST);
                        echo 'true';
                        exit(0);
                    }
                   else {
                       echo $check;
                       exit(0);
                   }

                }
            }
                echo $this->renderView('profile.html.twig',['user'=> $user]);
        }
        else {
            echo $this->renderView('loginregister.html.twig');
        }
    }
}
