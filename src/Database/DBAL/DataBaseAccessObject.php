<?php

namespace App\Database\DBAL;

use PDO;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class DataBaseAccessObject
{
    private ParameterBagInterface $parameterBag;

    private string $dbType;
    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPass;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->dbType = $this->parameterBag->get("db_type");
        $this->dbHost = $this->parameterBag->get("db_host");
        $this->dbName = $this->parameterBag->get("db_name");
        $this->dbUser = $this->parameterBag->get("db_user");
        $this->dbPass = $this->parameterBag->get("db_password");
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }
    
    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbUser()
    {
        return $this->dbUser;
    }

    public function getDbPassword()
    {
        return $this->dbPass;
    }

    /**
     * @return void|PDO $pdo
     */
    public function getPdo(?string $dbName)
    {
        $dsn = $this->dbType . ":host=" . $this->dbHost . ";";

        if ($dbName != null) {
            $dsn .= "dbname=" . $this->dbName . ";charset=utf8;";
        }

        $pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
        try {
        } catch (\PDOException $e) {

            // @todo
        } finally {

            return $pdo;
        }
    }
}
