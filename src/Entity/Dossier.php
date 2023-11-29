<?php

namespace App\Entity;

use App\Entity\Traits\TimestampBaseTrait;

class Dossier
{
    use TimestampBaseTrait;

    /**
     * primary key
     */
    private ?string $uuid = null;

    private ?string $name = null;

    private ?string $login = null;

    private ?string $password = null;

    public function __toString()
    {
        return $this->name;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Has to be properly encoded
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;

        return $this;
    }
}
 