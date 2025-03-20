<?php

namespace App\Entity;

use App\Entity\Pret;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get_simple"={
 *             "method"="GET",
 *             "path"="/adherents/{id}/simple",
 *             "normalization_context"={"groups"={"listAdherentSimple"}}
 *         },
 *         "get_full"={
 *             "method"="GET",
 *             "path"="/adherents/{id}/full",
 *             "normalization_context"={"groups"={"listAdherentFull"}}
 *         },
 *         "post"={
 *             "method"="POST",
 *             "path"="/adherents/{id}",
 *             "security"="is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"post_role_manager"}
 *         }
 *     },
 *         "StatNbPretsParAdherent"={
 *              "method"="GET",
 *              "route_name"="adherents_nbPrets",
 *              "controller"=StatsController::class
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/adherents/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"get_role_adherent"}
 *          }
 *     },
 *          "getNbPrets"={
 *              "method"="GET",
 *              "route_name"="adherent_prets_count"
 *      },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/adherents/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"put_role_admin"}}
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/adherents/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"delete_role_admin"}}
 *         },
 *         "patch"={
 *             "method"="PATCH",
 *             "path"="/adherents/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"patch_role_admin"}}
 *         },
 *     }
 * )
 */
class Adherent implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADHERENT = 'ROLE_ADHERENT';
    const DEFAULT_ROLE = 'ROLE_ADHERENT';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_role_adherent","listAdherentFull", "listAdherentSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listPretFull", "listPretSimple"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listPretFull", "listPretSimple"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\Length(max=255, maxMessage="L'adresse ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le code postal est obligatoire.")
     * @Assert\Length(min=5, max=5, exactMessage="Le code postal doit contenir {{ limit }} caractères.")
     * @Assert\Regex(pattern="/^\d{5}$/", message="Le code postal doit être composé de 5 chiffres.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="La ville est obligatoire.")
     * @Assert\Length(min=2, max=100, minMessage="La ville doit contenir au moins {{ limit }} caractères.", maxMessage="La ville ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\Regex(pattern="/^\d{10}$/", message="Le téléphone doit comporter 10 chiffres.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="L'email est obligatoire.")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide.")
     * @Assert\Length(max=255, maxMessage="L'email ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"get_role_adherent","post_role_manager","put_role_admin"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $password;

    /**
     * @ORM\Column(type="json", length=255, nullable=true)
     * [Assert\NotBlank]
     * @Groups({"get_role_adherent","get_role_manager","post_role_admin","put_role_admin"})
     */
    private ?array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="adherent")
     * @Groups({"listAdherentFull"})
     * @Groups({"get_role_adherent"})
     * @ApiSubresource
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $leRole[]=self::DEFAULT_ROLE;
        $this->roles= $leRole;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->codeCommune;
    }

    public function setCodeCommune(?string $codeCommune): self
    {
        $this->codeCommune = $codeCommune;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Pret>
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setAdherent($this);
        }
        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }
        return $this;
    }

   
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(){
        return $this->getMail();
    }

    public function eraseCredentials(){

    }
}
