<?php

namespace App\Entity;

use App\Repository\PlanningGardeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningGardeRepository::class)]
class PlanningGarde
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'planningGardes')]
    private ?Pharmacie $idPharmacie = null;

    

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPharmacie(): ?pharmacie
    {
        return $this->idPharmacie;
    }

    public function setIdPharmacie(?pharmacie $idPharmacie): static
    {
        $this->idPharmacie = $idPharmacie;

        return $this;
    }

  

   
}
