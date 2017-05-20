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

    public function getAddressByUserId($id)
    {

        $data = $this->DBManager->findAllSecure("SELECT * FROM addresses WHERE userid= :id", ['id' => $id]);
        return $data;
    }

    public function getAddressById($id)
    {

        $data = $this->DBManager->findOneSecure("SELECT * FROM addresses WHERE id= :id", ['id' => $id]);
        return $data;
    }

    public function getEmailByEmail($email)
    {
        $data = $this->DBManager->findAllSecure('SELECT email FROM users WHERE `email` = :email', ['email' => $email]);
        return $data;
    }

    public function getPhoneByPhone($phone)
    {
        $data = $this->DBManager->findAllSecure('SELECT phone FROM users WHERE `phone` = :phone', ['phone' => $phone]);
        return $data;
    }

    public function getPartnersByEmail($email)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM partners WHERE email = :email",
            ['email' => $email]);
        return $data;
    }

    public function getPartnersByPhone($phone)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM partners WHERE phone = :phone",
            ['phone' => $phone]);
        return $data;
    }
    public function getAllPartners()
    {
        $data = $this->DBManager->findAllSecure("SELECT * FROM partners ORDER BY `date` DESC");
        return $data;
    }

    public function checkDeleteAddress($data,$id)
    {
        if(empty($data['id'])){
            return 'Cette addresse n\'existe pas';
        }
        $address = $this->getAddressById($data['id']);
        if($address['defaultAddress'] !== 'false'){
            return 'Vous ne pouvez pas supprimer votre adresse par défaut';
        }
        if($address['userid'] !== $id )
            return 'Vous ne pouvez supprimer que vos adresses';

       return true;
    }

    public function deleteAddress($data)
    {
        $id = $data['id'];
        $data = $this->DBManager->doRequestSecure('DELETE   FROM `addresses` WHERE `id` = :id', ['id' => $id]);
        $write = $this->writeLog('access.log', ' => function : deleteAddress || User ' . $_SESSION['user_id'] . ' deleted an address.' . "\n");
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

    public function userAddressInsert($data)
    {
        if (isset($_SESSION['email'])) {
            $user = $this->getUserByEmail($_SESSION['email']);
        } else {
            $user = $this->getUserByEmail($data['email']);
        }

        $insert['userid'] = $user['id'];
        $insert['defaultAddress'] = $data['defaultAddress'];
        $insert['streetNumber'] = $data['streetNumber'];
        $insert['street'] = $data['route'];
        $insert['zipcode'] = $data['postalCode'];
        $insert['city'] = $data['city'];
        $insert['firstname'] = $data['firstname'];
        $insert['lastname'] = $data['lastname'];
        $insert['phone'] = $data['phone'];
        $this->DBManager->insert('addresses', $insert);
        $write = $this->writeLog('access.log', ' => function : userInsertAdress || User ' . $user['id'] . ' add an address' . "\n");

        return $insert;
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
        $write = $this->writeLog('access.log', ' => function : userLogin|| User ' . $data['id']. ' just loggin in.' . "\n");
        return true;
    }

    public function firstnameEdition($firstname)
    {
        $update['firstnameEditing'] = $firstname;
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure("UPDATE users SET `firstname`= :firstnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : firstnameEdition || User ' . $_SESSION['user_id'] . ' just updated his name to ' . $update['firstnameEditing'] . '.' . "\n");
        return true;
    }

    public function lastnameEdition($lastname)
    {
        $update['lastnameEditing'] = $lastname;
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure("UPDATE users SET `lastname`= :lastnameEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : lastnameEdition || User ' . $_SESSION['user_id'] . ' just updated his lastname to ' . $update['lastnameEditing'] . '.' . "\n");
        return true;
    }

    public function emailEdition($email)
    {
        $update['emailEditing'] = $email;
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure("UPDATE users SET `email`= :emailEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : emailEdition || User ' . $_SESSION['user_id'] . ' just updated his email to ' . $update['emailEditing'] . '.' . "\n");
        return true;
    }

    public function phoneEdition($phone)
    {
        $update['phoneEditing'] = $phone;
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure("UPDATE users SET `phone`= :phoneEditing WHERE `id` = :user_id", $update);
        $write = $this->writeLog('access.log', ' => function : phoneEdition || User ' . $_SESSION['user_id'] . ' just updated his phone to ' . $update['phoneEditing'] . '.' . "\n");
        return true;
    }

    public function defaultAddressEdition($id) {
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure('UPDATE addresses SET `defaultAddress`= "false" WHERE `userid` = :user_id AND `defaultAddress` = "true"', $update);
        $update['id'] = $id;
        $query = $this->DBManager->doRequestSecure('UPDATE addresses SET `defaultAddress`= "true" WHERE `userid` = :user_id AND `id` = :id', $update);
        $write = $this->writeLog('access.log', ' => function : changeDefaultAddress || User ' . $update['user_id'] . 'changed  his default address' ."\n");
    }

    public function newDefaultAddress() {
        $update['user_id'] = $_SESSION['user_id'];
        $query = $this->DBManager->doRequestSecure('UPDATE addresses SET `defaultAddress`= "false" WHERE `userid` = :user_id AND `defaultAddress` = "true"', $update);
        $write = $this->writeLog('access.log', ' => function : addDefaultAddress || User ' . $update['user_id'] . 'added a default address' ."\n");
    }

    public function userCheckFirstname($firstname)
    {

        if (empty($firstname)) {
            return 'Contenu manquant';
        }
        if (strlen($firstname) < 4) {
            return 'Prénom trop court';
        }

        return true;


    }

    public function userCheckLastname($lastname)
    {

        if (empty($lastname)) {
            return 'Contenu manquant';
        }
        if (strlen($lastname) < 4) {
            return 'Nom de famille trop court';
        }

        return true;
    }

    public function userCheckPhone($phone)
    {
        if (empty($phone)) {
            return 'Contenu manquant';
        }
        $testPhone = $this->getPhoneByPhone($phone);
        if ($testPhone)
            return 'Numéro de téléphone déjà utilisé';
        $phoneRegExp = '/(0|(\+33)|(0033))[1-9][0-9]{8}/';
        if (!preg_match($phoneRegExp, $phone))
            return "Votre numéro de téléphone ne semble pas valide.";

        return true;
    }


    public function userCheckEmail($email)
    {
        if (empty($email))
            return 'Contenu manquant';
        $testEmail = $this->getEmailByEmail($email);
        if ($testEmail)
            return 'Email déjà utilisé';
        $emailRegExp = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (!preg_match($emailRegExp, $email))
            return "Votre email ne semble pas valide.";

        return true;
    }

    public function userCheckDefaultAddress($id) {
        if (empty($id))
            return "Action interdite";
        $check = $this->getAddressById($id);
        if ($check["userid"] != $_SESSION['user_id'])
            return "L'adresse que vous souhaitez mettre par defaut ne vous appartient pas.";
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
    public function checkInsertPartners($data){
        if (empty($data['firstname']) OR empty($data['lastname']) OR empty($data['email']) OR empty($data['phone']))
            return "Des champs obligatoire ne sont pas remplis";
        $emailRegExp = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (!preg_match($emailRegExp, $data['email']))
            return "Votre email ne semble pas valide.";
        $phoneRegExp = '/(0|(\+33)|(0033))[1-9][0-9]{8}/';
        if (!preg_match($phoneRegExp, $data['phone']))
            return "Votre numéro de téléphone ne semble pas valide.";
        $check = $this->getPartnersByEmail($data['email']);
        if ($check !== false)
            return "Votre demande est en cours de traitement, nous reviendrons vers vous";
        $check = $this->getPartnersByPhone($data['phone']);
        if ($check !== false)
            return "Votre demande est en cours de traitement, nous reviendrons vers vous";
        return true;
    }



    public function insertPartners($data)
    {

        $partners['firstname'] = $data['firstname'];
        $partners['lastname'] = $data['lastname'];
        $partners['email'] = $data['email'];
        $partners['phone'] = $data['phone'];
        $partners['date'] = $this->giveDate();
        $this->DBManager->insert('partners', $partners);
        $write = $this->writeLog('access.log', ' => function : receivePartners || a a request has been sent'. "\n");
        return true;
    }

    public function checkDeletePartners($data,$id)
    {
        $user = $this->getUserById($id);
        if($user['role'] !=='admin')
            return 'Vous devez êtres admin pour pouvoir supprimé';
        if(empty($data['idPartners'])){
            return 'Ce plat n\'existe pas';
        }


        return true;
    }

    public function deletePartners($data,$user_id)
    {

        $id = $data['idPartners'];

        $data = $this->DBManager->doRequestSecure('DELETE   FROM `partners` WHERE `id` = :id', ['id' => $id]);
        $write = $this->writeLog('access.log', ' => function : deleteProduct || User ' . $user_id . 'deleted a product' ."\n");
        return $data;
    }
}
