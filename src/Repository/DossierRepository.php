<?php

namespace App\Repository;

use App\Database\DBO\AbstractRepository;
use App\Entity\Dossier;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class DossierRepository extends AbstractRepository
{
    public const TABLE_NAME = "dossier";
    public const PRIMARY_KEY = "uuid";

    public const COLUMN_NAME = "name";
    public const COLUMN_LOGIN = "login";
    public const COLUMN_PASSWORD = "password";
    public const COLUMN_UPDATED_AT = "updated_at";

    public const SQL_TABLE_CREATION = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        `" . self::PRIMARY_KEY . "` VARCHAR(36) PRIMARY KEY,
        `login` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL
    ); ";

    public function createOrUpdate(Dossier $dossier)
    {
        if ($dossier->getUuid() !== null) {

            $result = $this->executeInsertOrUpdate(
                self::TABLE_NAME,
                [
                    self::PRIMARY_KEY => $dossier->getUuid(),
                    self::COLUMN_NAME => $dossier->getName(),
                    self::COLUMN_LOGIN => $dossier->getLogin(),
                    self::COLUMN_PASSWORD => $dossier->getPassword(),
                ],
                [self::PRIMARY_KEY => $dossier->getUuid()]
            );
        } else {
            $dossier->setUuid(UuidV4::v4()->toRfc4122());
            $result = $this->executeInsertOrUpdate(
                self::TABLE_NAME,
                [
                    self::PRIMARY_KEY => $dossier->getUuid(),
                    self::COLUMN_NAME => $dossier->getName(),
                    self::COLUMN_LOGIN => $dossier->getLogin(),
                    self::COLUMN_PASSWORD => $dossier->getPassword(),
                ]
            );
        }

        if ($result) {
            return $dossier;
        }

        return false;
    }
}
