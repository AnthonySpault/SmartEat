<?php

namespace Model;

use PDO;
use PDOException;

class DBManager
{
    private $dbh;

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new DBManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->dbh = null;
    }

    private function connectToDb()
    {
        global $config;
        $db_config = $config['db_config'];
        $dsn = 'mysql:dbname='.$db_config['name'].';host='.$db_config['host'];
        $user = $db_config['user'];
        $password = $db_config['pass'];

        try {
            $dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }

        return $dbh;
    }

    protected function getDbh()
    {
        if ($this->dbh === null)
            $this->dbh = $this->connectToDb();
        return $this->dbh;
    }

    public function insert($table, $data = [])
    {
        $dbh = $this->getDbh();
        $query = 'INSERT INTO `' . $table . '` (';
        $first = true;
        foreach ($data as $k => $value) {
            if (!$first)
                $query .= ', ';
            else
                $first = false;
            $query .= '`'.$k.'`';
        }
        $query .= ') VALUES (';
        $first = true;
        foreach ($data AS $k => $value)
        {
            if (!$first)
                $query .= ', ';
            else
                $first = false;
            $query .= ':'.$k;
        }
        $query .= ')';
        try {
            $sth = $dbh->prepare($query);
            $sth->execute($data);
        } catch (Exception $e) {
            die("There's an error in the query!");
        }
        return true;
    }

    public function doRequestSecure($query, $data = []) {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        return $sth;
    }

    public function findOne($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $data->fetch();
        return $result;
    }

    public function findOneSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function findAll($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $data->fetchAll();
        return $result;
    }

    public function findAllSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
