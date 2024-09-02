<?php 

namespace App\Entity;

use App\Repository\PharmacieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: PharmacieRepository::class)]
class Pharmacie implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomPharma = null;

    #[ORM\Column(length: 255)]
    private ?string $addpharma = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $addmaps = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    

    #[ORM\OneToMany(targetEntity: PlanningGarde::class, mappedBy: 'idPharmacie')]
    private Collection $planningGardes;

    #[ORM\ManyToMany(targetEntity: Medicament::class, mappedBy: 'pharmacies')]
    private Collection $medicaments;
  

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureouvert = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureferme = null;

    // Autres propriétés et méthodes...


    public function __construct()
    {
        $this->planningGardes = new ArrayCollection();
        $this->medicaments = new ArrayCollection();
        
    }
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments->add($medicament);
            $medicament->addPharmacie($this);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        if ($this->medicaments->removeElement($medicament)) {
            $medicament->removePharmacie($this);
        }

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPharma(): ?string
    {
        return $this->nomPharma;
    }

    public function setNomPharma(string $nomPharma): static
    {
        $this->nomPharma = $nomPharma;

        return $this;
    }

    public function getAddpharma(): ?string
    {
        return $this->addpharma;
    }

    public function setAddpharma(string $addpharma): static
    {
        $this->addpharma = $addpharma;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

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

    public function getAddmaps(): ?string
    {
        return $this->addmaps;
    }

    public function setAddmaps(string $addmaps): static
    {
        $this->addmaps = $addmaps;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

 

   

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_PHARMACIEN']; // ou ajouter d'autres rôles si nécessaire
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
        return null; // Utiliser bcrypt ou argon2i qui ne nécessitent pas de sel
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires sur l'utilisateur, nettoyez-les ici
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getHeureouvert(): ?\DateTimeInterface
    {
        return $this->heureouvert;
    }

    public function setHeureouvert(?\DateTimeInterface $heureouvert): static
    {
        $this->heureouvert = $heureouvert;

        return $this;
    }

    public function getHeureferme(): ?\DateTimeInterface
    {
        return $this->heureferme;
    }

    public function setHeureferme(?\DateTimeInterface $heureferme): static
    {
        $this->heureferme = $heureferme;

        return $this;
    }
    
}
