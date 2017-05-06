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
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = " . $id);
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

    public function getEmail()
    {
        $data = $this->DBManager->findAllSecure('SELECT email FROM users');
        return $data;
    }

 public function getPlatesByName($name){
     $data = $this->DBManager->findOneSecure("SELECT * FROM plates WHERE name = :name",
         ['name' => $name]);
     return $data;
 }

    public function getNameById()
    {

        $data = $this->DBManager->findOneSecure("SELECT name FROM users WHERE id = ", ['user_id' => $_SESSION['user_id']]);
        return $data;
    }

    public function getAddressByUserId()
    {

        $data = $this->DBManager->findAllSecure("SELECT * FROM addresses WHERE userid= :user_id", ['user_id' => $_SESSION['user_id']]);
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

    public function userCheckAddress($data)
    {
        if (empty($data['streetNumber']) OR empty($data['route']) OR empty($data['city']) OR empty($data['postalCode'])) {
            return "Des champs obligatoire ne sont pas remplis";
        }

        if ($data['city'] !== "Paris") {
            return "SmartEat est disponible uniquement sur Paris pour le moment.";
        }

        return true;
    }

    public function addressEdition($data)
    {

        $update['id'] = $data['addressName'];
        $update['streetNumber'] = $data['streetNumber'];
        $update['street'] = $data['route'];
        $update['zipcode'] = $data['postalCode'];
        $update['city'] = $data['city'];
        $update['firstname'] = $data['firstname'];
        $update['lastname'] = $data['lastname'];
        $update['phone'] = $data['phone'];
        $query = $this->DBManager->findOneSecure("UPDATE addresses SET `streetNumber`= :streetNumber ,`street` = :street,`zipcode` = :zipcode,`city` = :city,`firstname`= :firstname,`lastname`= :lastname,`phone`= :phone WHERE `id` = :id", $update);
        return $query;
    }

    public function userAddressInsert($data)
    {
        if (isset($_SESSION['email'])) {
            $user = $this->getUserByEmail($_SESSION['email']);
        } else {
            $user = $this->getUserByEmail($data['email']);
        }

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
        $write = $this->writeLog('access.log', ' => function : userInsertAdress || User ' . $user['id'] . ' enter the adress : ' . $insert['name'] . "\n");
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
        $write = $this->writeLog('access.log', ' => function : userRegister || User ' . $user['lastname'] . ' ' . $user['firstname'] . ' just register.' . "\n");
        return true;
    }
    public function userCheckPlates($data, $img)
    {
        $valid = true;
        $errors = array();
        $extension= array();
        $extension = ['.jpeg','.png','.jpg','.PNG','.JPG','.JPEG'];


        $extFile = strrchr(basename($img['image']['name']), '.');
        if (empty($data['name']) OR empty($img['description']['name']) OR empty($data['description']) OR empty($data['ingredients']) OR empty($data['trick']) OR empty($data['price']) OR empty($data['category'])){
            return "Des champs obligatoire ne sont pas remplis";
        }
        if(!in_array($extFile,$extension)){
            return "Seul les images sont autoriséses";
        }
        $testName = $this->getPlatesByName($data['name']);
        if ($testName){
            return "Nom déjà utilisé";
        }

            return true;

    }

    public function insertPlates($data,$img)
    {
        $filepath = "uploads/plates_img/" . $data['name'] . strrchr(basename($img['image']['name']), '.');
        $user['name'] = $data['plateName'];
        $user['description'] = $data['description'];
        $user['ingredients'] = $data['ingredients'];
        $user['trick'] = $data['tricks'];
        $user['image'] = $filepath;
        $user['price'] =  $data['price'];
        $user['category'] =  $data['category'];
        $this->DBManager->insert('plates', $user);
        $write = $this->writeLog('access.log', ' => function : userRegister || User ' . $user['lastname'] . ' ' . $user['firstname'] . ' just register.' . "\n");
        $req = $this->getPlatesByName($data['name']);
        $update['image'] = "uploads/plates_img/" . $req['id'] . strrchr(basename($img['image']['name']), '.');
        $update['id']=$req['id'];
        $query = $this->DBManager->findOneSecure("UPDATE plates SET `image`= :image  WHERE `id` = :id", $update);
        move_uploaded_file($img['image']['tmp_name'], $update['image']);
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
        $_SESSION['email'] = $data['email'];
        return true;
    }

    public function firstnameEdition($data)
    {
        $update['firstnameEditing'] = $data['firstnameEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `firstname`= :firstnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : firstnameEdition || User ' . $_SESSION['user_id'] . ' just updated his name to ' . $update['firstnameEditing'] . '.' . "\n");
        return true;
    }

    public function lastnameEdition($data)
    {
        $update['lastnameEditing'] = $data['lastnameEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `lastname`= :lastnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : lastnameEdition || User ' . $_SESSION['user_id'] . ' just updated his lastname to ' . $update['lastnameEditing'] . '.' . "\n");
        return true;
    }

    public function emailEdition($data)
    {
        $update['emailEditing'] = $data['emailEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `email`= :emailEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : emailEdition || User ' . $_SESSION['user_id'] . ' just updated his email to ' . $update['emailEditing'] . '.' . "\n");
        return true;
    }

    public function phoneEdition($data)
    {
        $update['phoneEditing'] = $data['phoneEditing'];
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->findOneSecure("UPDATE users SET `phone`= :phoneEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : phoneEdition || User ' . $_SESSION['user_id'] . ' just updated his phone to ' . $update['phoneEditing'] . '.' . "\n");
        return true;
    }

    public function userCheckFirstname($data)
    {

        if (empty($data['firstnameEditing'])) {
            return 'Contenu manquant';
        }
        if (strlen($data['firstnameEditing']) < 4) {
            return 'Prénom trop court';
        }

        return true;


    }

    public function userCheckLastname($data)
    {

        if (empty($data['lastnameEditing'])) {
            return 'Contenu manquant';
        }
        if (strlen($data['lastnameEditing']) < 4) {
            return 'Nom de famille trop court';
        }

        return true;
    }

    public function userCheckPhone($data)
    {

        if (empty($data['phoneEditing'])) {
            return 'Contenu manquant';
        }
        $phoneRegExp = '/(0|(\+33)|(0033))[1-9][0-9]{8}/';
        if (!preg_match($phoneRegExp, $data['phoneEditing']))
            return "Votre numéro de téléphone ne semble pas valide.";

        return true;
    }


    public function userCheckEmail($data)
    {

        $testEmail = $this->getEmail();
        if (empty($data['emailEditing']))
            return 'Contenu manquant';
        foreach ($testEmail as $Key) {
            if ($Key === $data['emailEditing'])
                return 'Email déjà utilisé';
        }

        $emailRegExp = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (!preg_match($emailRegExp, $data['emailEditing']))
            return "Votre email ne semble pas valide.";

        return true;

    }


    public function giveDate()
    {
        $date = date("Y-m-d");
        $hours = date("H:i");
        return $date . " " . $hours;
    }

    public function writeLog($file, $text)
    {
        $date = $this->giveDate();
        $file_log = fopen('logs/' . $file, 'a');
        $log_info = $date . $text;
        fwrite($file_log, $log_info);
        fclose($file_log);
        return true;
    }
}
