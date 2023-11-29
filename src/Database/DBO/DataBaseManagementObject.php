<?php

namespace App\Database\DBO;

use App\Database\DBAL\DataBaseAccessObject;
use App\Repository\DossierRepository;
use App\Repository\EcritureRepository;

class DataBaseManagementObject extends DataBaseAccessObject
{
    public function createDatabase()
    {
        $pdo = $this->getPdo(null);

        if ($pdo instanceof \PDO) {

            $sql = "CREATE DATABASE IF NOT EXISTS `" . $this->getDbName() . "` COLLATE 'utf8mb4_general_ci';"
                . " CREATE USER IF NOT EXISTS '" . $this->getDbUser() . "'@'" . $this->getDbHost() . "' IDENTIFIED BY '" . $this->getDbPassword() . "';"
                . " GRANT ALL ON `" . $this->getDbName() . "`.* TO '" . $this->getDbUser() . "'@'" . $this->getDbHost() . "';"
                . " FLUSH PRIVILEGES;";

            $sqlReturn = $pdo->exec($sql);

            return $sqlReturn;
        }

        return null;
    }

    public function deleteDatabase()
    {
        $pdo = $this->getPdo(null);

        if ($pdo instanceof \PDO) {

            $sql = "DROP DATABASE IF EXISTS `" . $this->getDbName() . "`;"
                . " REVOKE ALL PRIVILEGES ON `" . $this->getDbName() . "`.* FROM '" . $this->getDbUser() . "'@'" . $this->getDbHost() . "';";

            $sqlReturn = $pdo->exec($sql);

            return $sqlReturn;
        }

        return null;
    }

    public function createTables()
    {
        $pdo = $this->getPdo($this->getDbName());

        if ($pdo instanceof \PDO) {

            $sql = EcritureRepository::SQL_TABLE_CREATION;
            $sql .= DossierRepository::SQL_TABLE_CREATION;

            $sql .= $this->getCreateForeignKeySQL(
                EcritureRepository::TABLE_NAME,
                DossierRepository::TABLE_NAME,
                EcritureRepository::FOREIGN_DOSSIER,
                DossierRepository::PRIMARY_KEY
            );

            $sqlReturn = $pdo->exec($sql);

            return $sqlReturn;
        }

        return null;
    }

    public function getCreateForeignKeySQL(string $sourceTable, string $targetTable, string $sourceKeyName, string $targetKeyName, string $onDelete = "CASCADE", string $onUpdate = "RESTRICT"): string
    {
        $sql = '';

        if (!empty($sourceTable) && !empty($targetTable) && !empty($sourceKeyName) && !empty($targetKeyName)) {

            $sql = "ALTER TABLE $sourceTable 
            ADD CONSTRAINT FK_$sourceTable" . "_$targetTable
            FOREIGN KEY IF NOT EXISTS ($sourceKeyName) REFERENCES $targetTable($targetKeyName) 
            ON DELETE $onDelete 
            ON UPDATE $onUpdate;";
        }

        return $sql;
    }

    public function loadFixtures() {
        
        $pdo = $this->getPdo($this->getDbName());

        if ($pdo instanceof \PDO) {

            $sql = DummyData::getRandomDossierSQLQuery(5);
  
            $sqlReturn = $pdo->exec($sql);

            return $sqlReturn;
        }
    }
}
