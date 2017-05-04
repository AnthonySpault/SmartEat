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

    public function getEmailByUserId($user_id)
    {
        $data = $this->DBManager->findOneSecure("SELECT email FROM users WHERE id = :user_id",
            ['user_id' => $user_id]);
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
        if (empty($data['streetNumber']) OR empty($data['route']) OR empty($data['city']) OR empty($data['postalCode'])){
            return "Des champs obligatoire ne sont pas remplis";
        }

        if ($data['city'] !== "Paris"){
            return "SmartEat est disponible uniquement sur Paris pour le moment.";
        }

        return true;
    }

    public function userAddressInsert($data) {
        $user = $this->getUserByEmail($data['email']);
        $insert['userid'] = $user['id'];
        $insert['name'] = $data['addressName'];
        $insert['streetNumber'] = $data['streetNumber'];
        $insert['street'] = $data['route'];
        $insert['zipcode'] = $data['postalCode'];
        $insert['city'] = $data['city'];
        $insert['firstname'] = $data['firstname'];
        $insert['lastname'] = $data['lastname'];
        $insert['phone'] = $data['phone'];
        $this->DBManager->insert('addresses', $insert);
        $write = $this->writeLog('access.log', ' => function : userInsertAdress || User ' . $user['id'] . ' enter the adress : ' .$insert['name'] . "\n");
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
        $write = $this->writeLog('access.log', ' => function : userRegister || User ' . $user['lastname'] . ' ' .$user['firstname'] .' just register.' . "\n");
        return true;
    }

    public function userCheckLogin($data)
    {

        if (empty($data['email']) OR empty($data['password']))
            return 'Champ(s) manquants';


        $user = $this->getUserByEmail($data['email']);
        if ($user === false)
            return 'Utilisateur non trouvé';

        if (!password_verify($data['password'], $user['password']))
            return 'Le mot de passe ne correspond pas à votre email';
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

    public function firstnameEdition($data)
    {
        $update['firstnameEditing'] = $data['firstnameEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `firstname`= :firstnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : firstnameEdition || User ' . $_SESSION['user_id'] . ' just updated his name to ' . $update['firstnameEditing'] . '.' . "\n");
        echo json_encode(array('success'=>true));
        exit(0);
    }

    public function lastnameEdition($data)
    {
        $update['lastnameEditing'] = $data['lastnameEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `lastname`= :lastnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : lastnameEdition || User ' . $_SESSION['user_id'] . ' just updated his lastname to ' . $update['lastnameEditing'] . '.' . "\n");
        echo json_encode(array('success'=>true));
        exit(0);
    }

    public function emailEdition($data)
    {
        $update['emailEditing'] = $data['emailEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `email`= :emailEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : emailEdition || User ' . $_SESSION['user_id'] . ' just updated his email to ' . $update['emailEditing'] . '.' . "\n");
        echo json_encode(array('success'=>true));
        exit(0);
    }
    public function phoneEdition($data)
    {
        $update['phoneEditing'] = $data['phoneEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `email`= :emailEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : emailEdition || User ' . $_SESSION['user_id'] . ' just updated his phone to ' . $update['phoneEditing'] . '.' . "\n");
        echo json_encode(array('success'=>true));
        exit(0);
    }

    public function userCheckFirstname($data)
    {
        $valid = true;
        $errors = array();
        if (empty($data['firstnameEditing'])){
            $valid = false;
            $errors['fields'] = 'Fields missing';
        }
        if(strlen($data['firstnameEditing']) < 4){
            $valid = false;
            $errors['fields'] = 'Prénom trop court';
        }
        if($valid === false){
            echo json_encode(array('success'=>false, 'errors'=>$errors));
            exit(0);
        }
        else{
            return true;
        }

    }

    public function userCheckLastname($data)
    {
        $valid = true;
        $errors = array();
        if (empty($data['lastnameEditing'])){
            $valid = false;
            $errors['fields'] = 'Fields missing';
        }
        if(strlen($data['lastnameEditing']) < 4){
            $valid = false;
            $errors['fields'] = 'Nom de famille trop court';
        }
        if($valid === false){
            echo json_encode(array('success'=>false, 'errors'=>$errors));
            exit(0);
        }
        else{
            return true;
        }

    }

    public function userCheckUsername($data)
    {
        $valid = true;
        $errors = array();
        if (empty($data['usernameEditing'])){
            $valid = false;
            $errors['fields'] = 'Fields missing';
        }
        if(strlen($data['usernameEditing']) < 4){
            $valid = false;
            $errors['fields'] = 'Pseudo trop court';
        }
        if(!$valid){
            echo json_encode(array('success'=>false, 'errors'=>$errors));
            exit(0);
        }else{
            return true;
        }
    }

    public function userCheckEmail($data){
        $valid = true;
        $errors = array();
        if (empty($data['emailEditing'])){
            $valid = false;
            $errors['fields'] = 'Fields missing';
        }
        $testEmail = $this->getEmailByUserId($data['emailEditing']);
        if (!$testEmail){
            $valid = false;
            $errors['email'] = 'Email déjà utilisé';
        }
        if(!$valid){
            echo json_encode(array('success'=>false, 'errors'=>$errors));
            exit(0);
        }else{
            return true;
        }
    }


    public function giveDate()
    {
        $date = date("Y-m-d");
        $hours = date("H:i");
        return $date." ".$hours;
    }

    public function writeLog($file, $text){
        $date = $this->giveDate();
        $file_log = fopen('logs/' . $file, 'a');
        $log_info = $date . $text;
        fwrite($file_log, $log_info);
        fclose($file_log);
        return true;
    }
}
