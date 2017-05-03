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
                if ($manager->userCheckLogin($_POST))
                {
                    $manager->userLogin($_POST['email']);
                    $this->redirect('profile');
                }
                else {
                    echo "Votre email et/ou votre mot de passe sont incorrects";
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

    public function logoutAction()
    {
        session_destroy();
        echo $this->redirect('login');
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
                        //$manager->userRegister($_POST);
                        //$manager->userAddressInsert($_POST);
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
        if (empty($_SESSION['user_id'])) {
            echo $this->renderView('loginregister.html.twig');
        }
        else {
            echo $this->renderView('profile.html.twig');
        }
    }
}
