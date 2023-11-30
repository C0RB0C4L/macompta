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
    public const COLUMN_CREATED_AT = "created_at";
    public const COLUMN_UPDATED_AT = "updated_at";

    public const SQL_TABLE_CREATION = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        `" . self::PRIMARY_KEY . "` VARCHAR(36) PRIMARY KEY,
        `login` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL
    ); ";

    /**
     * @return Dossier|false
     */
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

    /**
     * @return Dossier|null
     */
    public function selectOneDossier(string $uuid)
    {
        if (!empty($uuid)) {
            $result = $this->executeSelect(
                self::TABLE_NAME,
                [self::PRIMARY_KEY => $uuid],
                true
            );

            $dossier = new Dossier();
            $dossier->setUuid($result[self::PRIMARY_KEY]);
            $dossier->setName($result[self::COLUMN_NAME]);
            $dossier->setLogin($result[self::COLUMN_LOGIN]);
            $dossier->setPassword($result[self::COLUMN_PASSWORD]);
            $dossier->setUuid($result[self::PRIMARY_KEY]);
            $dossier->setCreatedAt(new \DateTimeImmutable($result[self::COLUMN_CREATED_AT]));
            $dossier->setUPdatedAt($result[self::COLUMN_UPDATED_AT]);

            return $dossier;
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function selectEcrituresFromDossier(string $uuid)
    {
        if (!empty($uuid)) {
            $result = $this->executeSelect(
                EcritureRepository::TABLE_NAME,
                [EcritureRepository::FOREIGN_DOSSIER => $uuid],
                false
            );

            return $result;
        }

        return null;
    }
}
