<?php

namespace App\Repository;

class DossierRepository
{
    public const TABLE_NAME = "dossier";
    public const PRIMARY_KEY = "uuid";

    public const SQL_TABLE_CREATION = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NAME . " (
        `" . self::PRIMARY_KEY . "` VARCHAR(36) PRIMARY KEY,
        `login` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `created_at` timestamp NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL
    ); ";
}
