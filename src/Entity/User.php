<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserController;
use App\Repository\UserRepository;
use App\Controller\UserAddController;
use App\Controller\UserListController;
use App\Controller\UserDeleteController;
use App\Controller\UserDetailController;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;

/** 
 *@ApiResource(
 *     normalizationContext={"groups"={"read:users:collection","read:users:item"}},
 *     collectionOperations={
 *         "get"={
 *                  "security"="is_granted('ROLE_USER')",
 *                  "method"="GET",
 *                  "path" ="/users",
 *                  "controller "=UserListController::class,
 *                  "normalization_context"={"groups"={"read:users:collection"}},
 *                  "openapi_context"={ 
 *                  "summary"="Consulter la liste des utilisateurs inscrits liés à un client sur le site web",
 *                  "description"="Consulter la liste des utilisateurs inscrits liés à un client sur le site web",
 *                  "security"={{"bearerAuth"={}}}
 *              }
 *         },
 *         "post"={
 *             "security"="is_granted('ROLE_USER')",
 *             "openapi_context"={
 *                  "summary"="Ajouter un nouvel utilisateur lié à un client ;",
 *                  "description"="Ajouter un nouvel utilisateur lié à un client ;",
 *                  "security"={{"bearerAuth"={}}}
 *              }
 *         }
 *     },
 *     itemOperations={
 *         "delete"={
 *             "security"="is_granted('ROLE_USER')",
 *             "method" = "DELETE",
 *             "read"=false,
 *             "controller"=UserDeleteController::class,
 *             "path" = "/users/{id}",
 *             "normalization_context"={"groups"={"read:users:item"}},
 *             "openapi_context"={
 *                  "summary"="Supprimer un utilisateur ajouté par un client.",
 *                  "description"="Supprimer un utilisateur ajouté par un client.",
 *                  "security"={{"bearerAuth"={}}}
 *             }
 *         },
 *         "get"={
 *             "security"="is_granted('ROLE_USER')",
 *             "method" ="GET",
 *             "read"=false,
 *             "controller"=UserDetailController::class,
 *             "path" = "/users/{id}",
 *             "normalization_context"={"groups"={"read:users:item"}},
 *             "openapi_context"={
 *                  "summary"="Consulter le détail d’un utilisateur inscrit lié à un client ",
 *                  "description"="Consulter le détail d’un utilisateur inscrit lié à un client ",
 *                  "security"={{"bearerAuth"={}}}
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="Un user ayant cette adresse email existe déjà")
 */
class User implements CustomerOwnedInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:users:collection","read:users:item"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:users:collection","read:users:item"})
     * @Assert\Length(min=3, minMessage="Le prénom doit faire entre 3 et 255 caractères", max=255, maxMessage="Le prénom doit faire entre 3 et 255 caractères")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:users:collection","read:users:item"})
     * @Assert\Length(min=3, minMessage="Le nom de famille doit faire entre 3 et 255 caractères", max=255, maxMessage="Le nom de famille doit faire entre 3 et 255 caractères")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"read:users:collection","read:users:item"})
     * @Assert\NotBlank(message="L'adresse email du customer est obligatoire")
     * @Assert\Email(message="Le format de l'adresse email doit être valide")
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:users:item"})
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:users:item"})
     */
    protected $customer;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
