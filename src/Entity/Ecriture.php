<?php

namespace App\Entity;

use App\Entity\Traits\TimestampBaseTrait;

class Ecriture
{
    use TimestampBaseTrait;

    public const TYPE = [
        "C" => "Crédit",
        "D" => "Débit"
    ];

    /**
     * primary key
     */
    private ?string $uuid = null;

    /**
     * foreign Key
     */
    private ?string $dossierUuid = null;

    private ?string $label = null;

    private ?string $date = null;

    private ?string $type = null;

    private ?float $amount = null;

    public function __toString()
    {
        return $this->label;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getDossierUuid()
    {
        return $this->dossierUuid;
    }

    public function setDossierUuid($dossierUuid): self
    {
        $this->dossierUuid = $dossierUuid;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
