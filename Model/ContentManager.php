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
}
