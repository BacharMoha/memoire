<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomMedoc = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Pharmacie>
     */
    #[ORM\ManyToMany(targetEntity: Pharmacie::class, inversedBy: 'medicaments')]
    private Collection $pharmacies;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    #[ORM\Column]
    private bool $disponible = true;

    // ... (autres méthodes)

    public function isDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }
    public function __construct()
    {
        $this->pharmacies = new ArrayCollection();
    }

    public function addPharmacie(Pharmacie $pharmacie): self
    {
        if (!$this->pharmacies->contains($pharmacie)) {
            $this->pharmacies->add($pharmacie);
            $pharmacie->addMedicament($this);
        }

        return $this;
    }

    public function removePharmacie(Pharmacie $pharmacie): self
    {
        if ($this->pharmacies->removeElement($pharmacie)) {
            $pharmacie->removeMedicament($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMedoc(): ?string
    {
        return $this->NomMedoc;
    }

    public function setNomMedoc(string $NomMedoc): static
    {
        $this->NomMedoc = $NomMedoc;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Pharmacie>
     */
    public function getPharmacies(): Collection
    {
        return $this->pharmacies;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
