<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Nationalite;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 * @ApiResource(
 *     itemOperations={
 *         "get_simple"={
 *             "method"="GET",
 *             "path"="/auteurs/{id}/simple",
 *             "normalization_context"={"groups"={"listAuteurSimple"}}
 *         },
 *          "get_full"={
 *             "method"="GET",
 *             "path"="/auteurs/{id}/full",
 *             "normalization_context"={"groups"={"listAuteurFull"}}
 *         },
 *         "post"={
 *             "method"="POST",
 *             "path"="/auteurs/{id}",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"post_role_manager"}}
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/auteurs/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_AUTEUR') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"get_role_auteur"}}
 *         },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/auteurs/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_AUTEUR') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"put_role_admin"}}
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/auteurs/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_AUTEUR') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"delete_role_admin"}}
 *         },
 *         "patch"={
 *             "method"="PATCH",
 *             "path"="/auteurs/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_AUTEUR') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"patch_role_admin"}}
 *         },
 *     }
 * )
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     */
    private $livres;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class)
     * @ORM\JoinColumn(name="nationalite_id", referencedColumnName="id")
     * @ApiSubresource(normalizationContext={"groups"={"nationalite_libelle"}})
     */
    private $nationalite;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
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

    /**
     * @Groups({"listAuteurFull"})
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    /**
     * @Groups({"listAuteurFull"})
     */
    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }    

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->nom . " " . $this->prenom;
    }

    /**
     * Retourne le nombre de livres de l'auteur
     * @Groups({"get"})
     * @return integer
     */
    public function getNbLivres() : int
    {
        return $this->livres->count();
    }

    /**
     * Retourne le nombre de livres disponibles de cet auteur
     * @Groups({"get"})
     * @return integer
     */
    public function getNbLivresDispo() : int
    {
        return array_reduce($this->livres->toArray(), function($nb, $livre){
            return $nb + ($livre->getDispo() === true ? 1: 0);
        }, 0);
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }
}
