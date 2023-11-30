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

    public const SQL_TABLE_CREATION = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        `" . self::PRIMARY_KEY . "` VARCHAR(36) PRIMARY KEY,
        `" . self::FOREIGN_DOSSIER . "` VARCHAR(36) NOT NULL,
        `label` VARCHAR(255) NOT NULL DEFAULT '',
        `date` date NOT NULL DEFAULT '0000-00-00',
        `type` enum('C', 'D') NOT NULL,
        `amount` DOUBLE(14,2) NOT NULL DEFAULT 0.00,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL
    );";

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
}
