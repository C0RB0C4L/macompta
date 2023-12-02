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
        `" . self::COLUMN_LOGIN . "` VARCHAR(255) NOT NULL,
        `" . self::COLUMN_PASSWORD . "` VARCHAR(255) NOT NULL,
        `" . self::COLUMN_NAME . "` VARCHAR(255) NOT NULL,
        `" . self::COLUMN_CREATED_AT . "` timestamp NULL DEFAULT current_timestamp(),
        `" . self::COLUMN_UPDATED_AT . "` timestamp NULL
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

            if (!empty($result)) {

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

    /**
     * @return array
     */
    public function selectAllDossier()
    {
        $result = $this->executeSelect(
            self::TABLE_NAME,
            [],
            false
        );
        return $result;
    }

    /**
     * Return an array of data with aditionnal properties, "credit, "debit", "balance"
     * The dossiers are sorted by creation date
     * 
     * @return array
     */
    public function selectAllDossierWithBalance()
    {
        $sql = "SELECT " . self::TABLE_NAME . ".*,"
            . " COALESCE(SUM(DISTINCT e_c." . EcritureRepository::COLUMN_AMOUNT . "), 0) AS credit,"
            . " COALESCE(SUM(DISTINCT e_d." . EcritureRepository::COLUMN_AMOUNT . "), 0) AS debit,"
            . " COALESCE(SUM(DISTINCT e_c." . EcritureRepository::COLUMN_AMOUNT . "), 0) - COALESCE(SUM(DISTINCT e_d." . EcritureRepository::COLUMN_AMOUNT . "), 0) AS balance,"
            . " COUNT(DISTINCT e_c." . EcritureRepository::PRIMARY_KEY . ") + COUNT(DISTINCT e_d." . EcritureRepository::PRIMARY_KEY . ") AS quantity"
            . " FROM " . self::TABLE_NAME
            . " LEFT OUTER JOIN " . EcritureRepository::TABLE_NAME . " AS e_c"
            . " ON " . self::TABLE_NAME . "." . self::PRIMARY_KEY . " = e_c." . EcritureRepository::FOREIGN_DOSSIER . " AND e_c." . EcritureRepository::COLUMN_TYPE . " = 'C'"
            . " LEFT OUTER JOIN " . EcritureRepository::TABLE_NAME . " AS e_d"
            . " ON " . self::TABLE_NAME . "." . self::PRIMARY_KEY . " = e_d." . EcritureRepository::FOREIGN_DOSSIER . " AND e_d." . EcritureRepository::COLUMN_TYPE . " = 'D'"
            . " GROUP BY " . self::TABLE_NAME . "." . self::PRIMARY_KEY
            . " ORDER BY " . self::TABLE_NAME . "." . self::COLUMN_CREATED_AT . " DESC";

        $result = $this->executeSelectStatement($sql);

        return $result;
    }
}
