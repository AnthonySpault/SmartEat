<?php

namespace Controller;

use Model\UserManager;
use Model\ContentManager;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['user_id'])) {
                $manager = UserManager::getInstance();
                $check = $manager->userCheckLogin($_POST);
                if ($check === true) {
                    $manager->userLogin($_POST['email']);
                    echo 'true';
                } else {
                    echo 'Votre email et/ou votre mot de passe sont incorrects';
                }
            } else {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['user_id'])) {
                $manager = UserManager::getInstance();
                $check = $manager->userCheckRegister($_POST);
                if ($check === true) {
                    $check = $manager->userCheckAddress($_POST);
                    if ($check) {
                        $manager->userRegister($_POST);
                        $manager->userAddressInsert($_POST);
                        echo 'true';
                        exit(0);
                    } else {
                        echo $check;
                        exit(0);
                    }
                } else {
                    echo $check;
                    exit(0);
                }
            } else {
                $this->redirect('profile');
            }
        } else {
            $this->redirect('profile');
        }
    }


    public function profileAction()
    {
        if (isset($_SESSION['user_id'])) {
            $userManager = UserManager::getInstance();
            $ContentManager = ContentManager::getInstance();
            $user = $userManager->getUserById($_SESSION['user_id']);
            $allAddress = $userManager->getAddressByUserId($_SESSION['user_id']);
            $allPlates = $ContentManager->getAllPlates();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['edit'] == "editProfile") {
                    if($_POST["kind"] == "firstname") {
                        $check = $userManager->userCheckFirstname($_POST['value']);
                        if ($check === true) {
                            $userManager->firstnameEdition($_POST['value']);
                            echo 'true';
                            exit(0);
                        } else {
                            echo $check;
                            exit(0);
                        }
                    }
                    if ($_POST["kind"] == "lastname") {
                        $check = $userManager->userCheckLastname($_POST['value']);
                        if ($check === true) {
                            $userManager->lastnameEdition($_POST['value']);
                            echo 'true';
                            exit(0);
                        } else {
                            echo $check;
                            exit(0);
                        }
                    }
                    if ($_POST["kind"] == "email") {
                        $check = $userManager->userCheckEmail($_POST['value']);
                        if ($check === true) {

                            $userManager->emailEdition($_POST['value']);
                            echo 'true';
                            exit(0);
                        } else {
                            echo $check;
                            exit(0);
                        }

                    }
                    if ($_POST["kind"] == "phone") {
                        $check = $userManager->userCheckPhone($_POST['value']);
                        if ($check === true) {
                            $userManager->phoneEdition($_POST['value']);
                            echo 'true';
                            exit(0);
                        } else {
                            echo $check;
                            exit(0);
                        }
                    }
                    if ($_POST["kind"] == "changeDefaultAddress") {
                        $check = $userManager->userCheckDefaultAddress($_POST['value']);
                        if ($check === true) {
                            $userManager->defaultAddressEdition($_POST['value']);
                            echo "true";
                            exit(0);
                        } else {
                            echo $check;
                            exit(0);
                        }
                    }
                }

                /*if (isset($_POST['plateName'])) {

                    $check = $ContentManager->userCheckPlates($_POST, $_FILES);
                    if ($check === true) {
                        $ContentManager->insertPlates($_POST, $_FILES);
                        echo 'true';
                        exit(0);
                    } else {
                        echo $check;
                        exit(0);
                    }
                }

                if (isset($_POST['firstnameAddressEdition'])) {
                    $check = $userManager->userCheckAddress($_POST);
                    if ($check === true) {
                        $userManager->addressEdition($_POST);
                        echo 'true';
                        exit(0);
                    } else {
                        echo $check;
                        exit(0);
                    }

                }

                if (isset($_POST['firstnameAddress'])) {
                    $check = $userManager->userCheckAddress($_POST);
                    if ($check === true) {
                        $userManager->userAddressInsert($_POST);
                        echo 'true';
                        exit(0);
                    } else {
                        echo $check;
                        exit(0);
                    }


                }*/
            }
            echo $this->renderView('profile.html.twig', ['user' => $user, 'allAddress' => $allAddress,'allPlates'=> $allPlates]);
        } else {
            echo $this->renderView('loginregister.html.twig');
        }
    }
}
