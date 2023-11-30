<?php

namespace App\Database\DBO;

use App\Database\DBAL\DataBaseAccessObject;

abstract class AbstractRepository extends DataBaseAccessObject
{
    protected $customPdo;

    public function executeInsertOrUpdate(string $table, array $data, array $where = [])
    {
        $sql = "";
        if ($where == []) {

            $parametersColumns = $this->getOrdainedColumns($data);
            $values = $this->getInsertPreparedValues($data);

            $sql = "INSERT INTO $table ($parametersColumns) VALUES ("
                . $values
                . ");";
        } else {
            $preparedValues = $this->getUpdatePreparedValues($data);
            $sql = "UPDATE $table SET "
                . "$preparedValues"
                . " WHERE " . array_key_first($where) . " = :" . array_key_first($where);
        }
        dump($data);

        return $this->executePrepared($sql, $data);
    }

    private function getOrdainedColumns(array $array)
    {
        $string = "";

        foreach ($array as $key => $value) {
            $string .= "$key,";
        }
        $string = substr($string, 0, -1);

        return $string;
    }

    private function getInsertPreparedValues(array $array)
    {
        $string = "";

        foreach ($array as $key => $value) {
            $string .= ":$key,";
        }
        $string = substr($string, 0, -1);

        return $string;
    }

    private function getUpdatePreparedValues(array $array)
    {
        $string = "";

        foreach ($array as $key => $value) {
            $string .= "$key = :$key, ";
        }
        $string = substr($string, 0, -2);

        return $string;
    }

    private function executePrepared($sql, array $data)
    {
        $pdo = $this->getPdo($this->getDbName());

        if ($pdo instanceof \PDO) {
            $statement = $pdo->prepare($sql);
            if ($statement !== false) {

                try {
                    $execute = $statement->execute($data);

                    return $execute;
                } catch (\Throwable $th) {
                    return false;
                }
            }
        }

        return false;
    }
}
