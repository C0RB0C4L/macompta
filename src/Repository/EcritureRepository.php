<?php

namespace App\Repository;

use App\Database\DBO\AbstractRepository;
use App\Entity\Ecriture;
use Symfony\Component\Uid\Uuid;

class EcritureRepository extends AbstractRepository
{
    public const TABLE_NAME = "ecriture";
    public const PRIMARY_KEY = "uuid";
    public const FOREIGN_DOSSIER = "dossier_uuid";

    public const COLUMN_LABEL = "label";
    public const COLUMN_DATE = "date";
    public const COLUMN_TYPE = "type";
    public const COLUMN_AMOUNT = "amount";
    public const COLUMN_CREATED_AT = "created_at";
    public const COLUMN_UPDATED_AT = "updated_at";

    public const SQL_TABLE_CREATION = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        `" . self::PRIMARY_KEY . "` VARCHAR(36) PRIMARY KEY,
        `" . self::FOREIGN_DOSSIER . "` VARCHAR(36) NOT NULL,
        `" . self::COLUMN_LABEL . "` VARCHAR(255) NOT NULL DEFAULT '',
        `" . self::COLUMN_DATE . "` date NOT NULL DEFAULT '0000-00-00',
        `" . self::COLUMN_TYPE . "` enum('C', 'D') NOT NULL,
        `" . self::COLUMN_AMOUNT . "` DOUBLE(14,2) NOT NULL DEFAULT 0.00,
        `" . self::COLUMN_CREATED_AT . "` timestamp NULL DEFAULT current_timestamp(),
        `" . self::COLUMN_UPDATED_AT . "` timestamp NULL
    );";

    /**
     * @return Ecriture|false
     */
    public function createOrUpdate(Ecriture $ecriture)
    {
        if ($ecriture->getUuid() !== null) {
            //  mise à jour d'une écriture existante
            $result = $this->executeInsertOrUpdate(
                self::TABLE_NAME,
                [
                    self::PRIMARY_KEY => $ecriture->getUuid(),
                    self::FOREIGN_DOSSIER => $ecriture->getDossierUuid(),
                    self::COLUMN_LABEL => $ecriture->getLabel(),
                    self::COLUMN_DATE => $ecriture->getDate(),
                    self::COLUMN_TYPE => $ecriture->getType(),
                    self::COLUMN_AMOUNT => $ecriture->getAmount(),
                ],
                [self::PRIMARY_KEY => $ecriture->getUuid()]
            );
        } else {
            // création d'une nouvelle écriture
            $ecriture->setUuid(Uuid::v4()->toRfc4122());
            $result = $this->executeInsertOrUpdate(
                self::TABLE_NAME,
                [
                    self::PRIMARY_KEY => $ecriture->getUuid(),
                    self::FOREIGN_DOSSIER => $ecriture->getDossierUuid(),
                    self::COLUMN_LABEL => $ecriture->getLabel(),
                    self::COLUMN_DATE => $ecriture->getDate(),
                    self::COLUMN_TYPE => $ecriture->getType(),
                    self::COLUMN_AMOUNT => $ecriture->getAmount(),
                ]
            );
        }

        if ($result) {
            return $ecriture;
        }

        return false;
    }

    /**
     * @return array
     */
    public function selectAllEcritures()
    {
        $result = $this->executeSelect(
            self::TABLE_NAME,
            [],
            false
        );
        return $result;
    }

    /**
     * @return Ecriture|null
     */
    public function selectOneEcriture(string $uuid)
    {
        if (!empty($uuid)) {
            $result = $this->executeSelect(
                self::TABLE_NAME,
                [self::PRIMARY_KEY => $uuid],
                true
            );

            $ecriture = new Ecriture();
            $ecriture->setUuid($result[self::PRIMARY_KEY]);
            $ecriture->setDossierUuid($result[self::FOREIGN_DOSSIER]);
            $ecriture->setLabel($result[self::COLUMN_LABEL]);
            $ecriture->setDate($result[self::COLUMN_DATE]);
            $ecriture->setType($result[self::COLUMN_TYPE]);
            $ecriture->setAmount($result[self::COLUMN_AMOUNT]);
            $ecriture->setCreatedAt(new \DateTimeImmutable($result[self::COLUMN_CREATED_AT]));
            $ecriture->setUPdatedAt($result[self::COLUMN_UPDATED_AT]);

            return $ecriture;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function deleteEcriture(string $uuid)
    {
        $result = $this->executeDelete(
            self::TABLE_NAME,
            [self::PRIMARY_KEY => $uuid]
        );

        return $result;
    }
}
