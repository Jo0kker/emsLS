<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class PasswordUpdate
{

    /**
     *
     */
    private $oldPassword;

    /**
     *
     */
    private $newPassword;

    /**
     *
     */
    private $confPassword;


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfPassword(): ?string
    {
        return $this->confPassword;
    }

    public function setConfPassword(string $confPassword): self
    {
        $this->confPassword = $confPassword;

        return $this;
    }
}
