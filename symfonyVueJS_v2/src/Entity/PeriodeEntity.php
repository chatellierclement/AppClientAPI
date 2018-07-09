<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeriodeEntityRepository")
 */
class PeriodeEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $debutJournee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $finJournee;

    
    public function getDebutJournee(): ?string
    {
        return $this->debutJournee;
    }

    public function setDebutJournee(string $debutJournee): self
    {
        $this->debutJournee = $debutJournee;

        return $this;
    }

    public function getFinJournee(): ?string
    {
        return $this->finJournee;
    }

    public function setFinJournee(string $finJournee): self
    {
        $this->finJournee = $finJournee;

        return $this;
    }

   
}
