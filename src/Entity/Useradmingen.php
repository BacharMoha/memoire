<?php

namespace App\Entity;

use App\Repository\UseradmingenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UseradmingenRepository::class)]
class Useradmingen implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(type: 'json')]
    private array $roles = []; // Stocke les rôles en tant que tableau JSON

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function getPassword(): ?string
    {
        return $this->mdp; // Renvoie le mot de passe encodé
    }

    public function setPassword(string $password): static
    {
        $this->mdp = $password;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // ou $this->username, selon votre logique
    }

    public function eraseCredentials(): void
    {
        // Efface les données sensibles ici si nécessaire, sinon laissez vide
    }

    public function getRoles(): array
    {
        // Assure-vous que chaque utilisateur a au moins un rôle. Ici, le rôle par défaut est "ROLE_USER".
        $roles = $this->roles;
        // Garantit que chaque utilisateur a le rôle "ROLE_USER" au moins
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
