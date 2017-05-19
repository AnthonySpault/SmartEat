<?php

namespace Model;

class ContentManager
{
    private $DBManager;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new ContentManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }

    public function getAllPlates()
    {
        $data = $this->DBManager->findAllSecure('SELECT * FROM plates');
        return $data;
    }

    public function getOnePlates($id)
    {
        $data = $this->DBManager->findOneSecure('SELECT * FROM plates WHERE id = :id', ['id' => $id]);
        return $data;
    }

    public function getCurrentPlates()
    {
        $data = $this->DBManager->findAllSecure('SELECT * FROM plates WHERE status = "active"');
        return $data;
    }

    public function getPlatesByName($name)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM plates WHERE name = :name",
            ['name' => $name]);
        return $data;
    }

    public function checkDeletePlates($data,$id)
    {
        if(empty($data['id'])){
            return 'Ce plat n\'existe pas';
        }
        $plate = $this->getOnePlates($data['id']);
        if($plate['status'] === 'active'){
            return 'Vous ne pouvez pas supprimer un plat actif sur notre site';
        }

        return true;
    }

    public function deletePlates($data)
    {
        $id = $data['id'];
        $data = $this->DBManager->doRequestSecure('DELETE   FROM `plates` WHERE `id` = :id', ['id' => $id]);
        return $data;
    }

    public function userCheckPlates($data, $img)
    {
        $extension = ['.jpeg', '.png', '.jpg', '.PNG', '.JPG', '.JPEG'];
        $extFile = strrchr(basename($img['file']['name']), '.');
        if (empty($data['plateName']) OR empty($img['file']['name']) OR empty($data['description']) OR empty($data['ingredients']) OR empty($data['tricks']) OR empty($data['price']) OR empty($data['category'])) {
            return "Des champs obligatoire ne sont pas remplis";
        }
        if (!in_array($extFile, $extension)) {
            return "Seul les images sont autoriséses";
        }
        $manager = ContentManager::getInstance();
        $testName = $manager->getPlatesByName($data['plateName']);
        if ($testName) {
            return "Nom déjà utilisé";
        }
        return true;
    }

    public function insertPlates($data, $img)
    {
        $filepath = "uploads/plates_img/" . $data['plateName'] . strrchr(basename($img['file']['name']), '.');
        $plates['name'] = $data['plateName'];
        $plates['description'] = $data['description'];
        $plates['ingredients'] = $data['ingredients'];
        $plates['trick'] = $data['tricks'];
        $plates['image'] = $filepath;
        $plates['price'] = str_replace(",",".",$data['price']);
        $plates['category'] = $data['category'];
        $plates['status'] = 'inactive';
        $this->DBManager->insert('plates', $plates);

        $req = $this->getPlatesByName($data['plateName']);
        $update['image'] = 'uploads/plates_img/' . $req['id'] . strrchr(basename($img['file']['name']), '.');
        $update['id'] = $req['id'];
        $query = $this->DBManager->findOneSecure("UPDATE plates SET `image`= :image  WHERE `id` = :id", $update);
        move_uploaded_file($img['file']['tmp_name'], $update['image']);
        return $plates;
    }
}
