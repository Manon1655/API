<?php

namespace App\Entity;

use App\Entity\Pret;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ApiResource(
 *     itemOperations={
 *         "get_simple"={
 *             "method"="GET",
 *             "path"="/adherents/{id}/simple",
 *             "normalization_context"={"groups"={"listAdherentSimple"}}
 *         },
 *          "get_full"={
 *             "method"="GET",
 *             "path"="/adherents/{id}/full",
 *             "normalization_context"={"groups"={"listAdherentFull"}}
 *         },
 *         "post"={
 *             "method"="POST",
 *             "path"="/adherents/{id}",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"post_role_manager"}}
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/adherents/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"get_role_adherent"}}
 *         },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/adherents/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"put_role_admin"}}
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/adherents/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"delete_role_admin"}}
 *         },
 *         "patch"={
 *             "method"="PATCH",
 *             "path"="/adherents/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"patch_role_admin"}}
 *         },
 *     }
 * )
 */
class Adherent implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listPretFull", "listPretSimple"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listPretFull", "listPretSimple"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\Length(max=255, maxMessage="L'adresse ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le code postal est obligatoire.")
     * @Assert\Length(min=5, minMessage="Le code postal doit contenir {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="La ville est obligatoire.")
     * @Assert\Length(max=255, maxMessage="La ville ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le téléphone est obligatoire.")
     * @Assert\Length(max=255, maxMessage="Le téléphone ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="L'email est obligatoire.")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide.")
     * @Assert\Length(max=255, maxMessage="L'email ne doit pas dépasser {{ limit }} caractères.")
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

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

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array<Role|string> The user roles
     */
    public function getRoles(){
        return ['ROLE_USER'];
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
