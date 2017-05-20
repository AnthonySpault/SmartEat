<?php

namespace Model;

class ContentManager
{
    private $DBManager;
    private $UserManager;

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
        $this->UserManager = UserManager::getInstance();
    }

    public function getAllPlates()
    {
        $data = $this->DBManager->findAllSecure('SELECT * FROM plates ORDER BY `status`,`category`');
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
        $id = $_SESSION['user_id'];
        $id = $data['id'];
        $plate = $this->getOnePlates($data['id']);
        $data = $this->DBManager->doRequestSecure('DELETE   FROM `plates` WHERE `id` = :id', ['id' => $id]);
        $write = $this->UserManager->writeLog('access.log', ' => function : deleteProduct || User ' . $id . 'deleted a product' ."\n");
        unlink($plate['image']);
        return $data;
    }

    public function checkPlates($data, $img)
    {
        $extension = ['.jpeg', '.png', '.jpg', '.PNG', '.JPG', '.JPEG'];
        $extFile = strrchr(basename($img['file']['name']), '.');
        if (empty($data['plateName']) OR empty($img['file']['name']) OR empty($data['description']) OR empty($data['allergenes']) OR empty($data['ingredients']) OR empty($data['tricks']) OR empty($data['price']) OR empty($data['category'])) {
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
        $id = $_SESSION['user_id'];
        $filepath = "uploads/plates_img/" . $data['plateName'] . strrchr(basename($img['file']['name']), '.');
        $plates['name'] = $data['plateName'];
        $plates['description'] = $data['description'];
        $plates['ingredients'] = $data['ingredients'];
        $plates['allergenes'] = $data['allergenes'];
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
        $write = $this->UserManager->writeLog('access.log', ' => function : insertPlates || User ' . $id . 'inserted  a product' ."\n");
        return $plates;
    }
    public function insertPlatesEdition($data,$img)
    {
        $id = $_SESSION['user_id'];
        $plates['id'] = $data['idEditing'];
        $plateWithId = $this->getOnePlates($plates['id']);
        if(!empty($img['fileEditing']['name'])){
            unlink($plateWithId['image']);
        }

        $plates['nameEditing'] = $data['plateNameEditing'];
        $plates['description'] = $data['descriptionEditing'];
        $plates['ingredients'] = $data['ingredientsEditing'];
        $plates['allergenes'] = $data['allergenesEditing'];
        $plates['trick'] = $data['tricksEditing'];
        $plates['price'] = str_replace(",",".",$data['priceEditing']);
        $plates['category'] = $data['categoryEditing'];
        $query = $this->DBManager->findOneSecure("UPDATE plates SET `name` = :nameEditing, `description` = :description, `ingredients` = :ingredients, `allergenes` = :allergenes, `trick` = :trick, `price` = :price,`category` = :category  WHERE `id` = :id", $plates);
        $req = $this->getPlatesByName($plates['nameEditing']);
        $update['image'] = 'uploads/plates_img/' . $req['id'] . strrchr(basename($img['fileEditing']['name']), '.');
        $update['id'] = $req['id'];
        $query = $this->DBManager->findOneSecure("UPDATE plates SET `image`= :image  WHERE `id` = :id", $update);
        move_uploaded_file($img['fileEditing']['tmp_name'], $update['image']);
        $write = $this->UserManager->writeLog('access.log', ' => function : editPlates || User ' . $id . 'edited  a product' ."\n");
        return $plates;
    }
    public function checkPlatesEdition($data,$img)
    {
        $extension = ['.jpeg', '.png', '.jpg', '.PNG', '.JPG', '.JPEG'];
        $extFile = strrchr(basename($img['fileEditing']['name']), '.');
        if (empty($data['plateNameEditing'])  OR empty($data['descriptionEditing']) OR empty($data['allergenesEditing']) OR empty($data['ingredientsEditing']) OR empty($data['tricksEditing']) OR empty($data['priceEditing']) OR empty($data['categoryEditing'])) {
            return "Des champs obligatoire ne sont pas remplis";
        }
    if(!empty($img['fileEditing']['name'])){
        if (!in_array($extFile, $extension)) {
            return "Seul les images sont autoriséses";
        }
    }


        return true;
    }
    public function checkUpdateStatus($data){
        if(empty($data['idStatus']) OR empty($data['status'])) {
            return 'Des champs sont manquants';
        }
        return true;


    }

    public function updateStatus($data){
        $id = $_SESSION['user_id'];
        $update['id'] = $data['idStatus'];
        $update['status'] = $data['status'];
        $query = $this->DBManager->doRequestSecure("UPDATE plates SET `status`= :status WHERE `id` = :id", $update);
        $write = $this->UserManager->writeLog('access.log', ' => function : insertPlates || User ' . $id . 'updated a status' ."\n");
    }
}
