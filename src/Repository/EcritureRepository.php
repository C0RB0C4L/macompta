<?php

namespace App\Repository;

class EcritureRepository
{
    public const TABLE_NAME = "ecriture";
    public const PRIMARY_KEY = "uuid";
    public const FOREIGN_DOSSIER = "dossier_uuid";

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
}
