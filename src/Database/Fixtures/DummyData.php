<?php

namespace App\Database\Fixtures;

use App\Repository\DossierRepository;
use App\Repository\EcritureRepository;
use Symfony\Component\Uid\Uuid;

class DummyData
{
    public static function getRandomDossierSQLQuery(int $lines)
    {
        $sql = "";

        if ($lines > 0) {
            $lines > 20 ? $lines = 20 : $lines;

            for ($i = 0; $i < $lines; $i++) {
                $sql .= "INSERT INTO " . DossierRepository::TABLE_NAME
                    . "(uuid, login, password, name, created_at) VALUES";

                $uuid = Uuid::v4();
                $login = "login_" . (string)rand(0, 999);
                $password = md5($login);
                $name = "name_" . (string)rand(0, 999);
                $createdAt = "now()";

                $sql .= "("
                    . "'" . $uuid->toRfc4122() . "', "
                    . "'" . $login . "', "
                    . "'" . $password . "', "
                    . "'" . $name . "', "
                    . $createdAt
                    . "),";

                $sql = substr($sql, 0, -1);
                $sql .= ";";

                $sql .= self::getRandomEcritureSQLQuery(rand(1, 9), $uuid);
            }
        }

        return $sql;
    }

    public static function getRandomEcritureSQLQuery(int $lines, string $dossierUuid)
    {
        $sql = "";

        if ($lines > 0) {
            $lines > 20 ? $lines = 20 : $lines;

            $sql = "INSERT INTO " . EcritureRepository::TABLE_NAME
                . "(uuid, dossier_uuid, label, date, type, amount, created_at) VALUES";

            for ($i = 0; $i < $lines; $i++) {
                $uuid = Uuid::v4();
                $label = "label_" . (string)rand(0, 999);
                $date = date("Y-m-d");
                $type = (rand(1, 100) > 50 ? "C" : "D");
                $amount = ($type == "C" ? rand(1, 10000) : rand(-10000, -1));
                $createdAt = "now()";

                $sql .= "("
                    . "'" . $uuid->toRfc4122() . "', "
                    . "'" . $dossierUuid . "', "
                    . "'" . $label . "', "
                    . "'" . $date . "', "
                    . "'" . $type . "', "
                    . "'" . $amount . "', "
                    . $createdAt
                    . "),";
            }

            $sql = substr($sql, 0, -1);
            $sql .= ";";
        }

        return $sql;
    }
}
