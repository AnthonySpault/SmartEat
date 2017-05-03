<?php

namespace Model;

class UserManager
{
    private $DBManager;

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new UserManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }

    public function getUserById($id)
    {
        $id = (int)$id;
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = ".$id);
        return $data;
    }

    public function getUserByEmail($email)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM users WHERE email = :email",
                                ['email' => $email]);
        return $data;
    }

    public function getUserByPhone($phone)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM users WHERE phone = :phone",
                                ['phone' => $phone]);
        return $data;
    }

    public function userCheckRegister($data)
    {
        if (empty($data['firstname']) OR empty($data['lastname']) OR empty($data['email']) OR empty($data['password']) OR empty($data['passwordconfirm']) OR empty($data['phone']))
            return "Des champs obligatoire ne sont pas remplis";
        $check = $this->getUserByEmail($data['email']);
        if ($check !== false)
            return "Un compte avec cette adresse email existe déja";
        $check = $this->getUserByPhone($data['phone']);
        if ($check !== false)
            return "Un compte avec ce numéro de téléphone existe déja";
        $emailRegExp = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (!preg_match($emailRegExp, $data['email']))
            return "Votre email ne semble pas valide.";
        $phoneRegExp = '/(0|(\+33)|(0033))[1-9][0-9]{8}/';
        if (!preg_match($phoneRegExp, $data['phone']))
            return "Votre numéro de téléphone ne semble pas valide.";
        if (strlen($data['password']) < 8)
            return "Votre mot de passe est trop court. Celui ci doit faire minimum 8 caractères.";
        if ($data['passwordconfirm'] !== $data['password'])
            return "Votre mot de passe et sa confirmation ne semblent pas identiques";
        return true;
    }

    public function userCheckAddress($data) {
        if (empty($data['streetNumber']) OR empty($data['route']) OR empty($data['city']) OR empty($data['postalCode']))
            return "Des champs obligatoire ne sont pas remplis";
        if ($data['city'] !== "Paris")
            return "SmartEat est disponible uniquement sur Paris pour le moment.";
        return true;
    }

    public function userAddressInsert($data) {
        $user = $this->getUserByEmail($data['email']);
        $insert['userid'] = $user['id'];
        $insert['name'] = "Adresse par défaut";
        $insert['streetNumber'] = $data['streetNumber'];
        $insert['street'] = $data['route'];
        $insert['zipcode'] = $data['postalCode'];
        $insert['city'] = $data['city'];
        $insert['firstname'] = $data['firstname'];
        $insert['lastname'] = $data['lastname'];
        $this->DBManager->insert('addresses', $insert);
    }

    private function userHash($pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        return $hash;
    }

    public function userRegister($data)
    {
        $user['role'] = "client";
        $user['firstname'] = $data['firstname'];
        $user['lastname'] = $data['lastname'];
        $user['email'] = $data['email'];
        $user['phone'] = $data['phone'];
        $user['password'] = $this->userHash($data['password']);
        $user['points'] = "10";
        $this->DBManager->insert('users', $user);
        return true;
    }

    public function userCheckLogin($data)
    {
        if (empty($data['email']) OR empty($data['password']))
            return false;
        $user = $this->getUserByEmail($data['email']);
        if ($user === false)
            return false;
        if (!password_verify($user['password'], $user['password']))
            return false;
        return true;
    }

    public function userLogin($email)
    {
        $data = $this->getUserByEmail($email);
        if ($data === false)
            return false;
        $_SESSION['user_id'] = $data['id'];
        return true;
    }
}
