<?php

namespace App\Entity;

use App\Entity\Pret;
use App\Entity\Genre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource(
 *      attributes={
 *          "order"={"titre"="ASC"}  
 *      },
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/livres",
 *              "normalization_context"={
 *                  "groups"={"get_role_adherent"}
 *              }
 *          },
 *          "post"={
 *             "method"="POST",
 *             "path"="/genres/{id}",
 *             "security"="is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *         }
 *      },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/livres/{id}",
 *             "normalization_context"={
 *                  "groups"={"get_role_adherent"}
 *             }
 *         },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/livres/{id}",
 *             "security"="is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous n'avez pas les droits d'accéder à cette ressource",
 *             "denormalization_context"={
 *                  "groups"={"put_manager"}
 *             }
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/livres/{id}",
 *             "security"="is_granted('ROLE_ADMIN')",
 *             "security_message"="Vous n'avez pas les droits d'accéder à cette ressource"
 *         }
 *     }
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=17, unique=true)
     * @Assert\Isbn(message="L'ISBN doit être un format valide.")
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le titre du livre ne peut pas être vide.")
     * @Assert\Length(
     *      min=2,
     *      max=100,
     *      minMessage="Le titre du livre doit contenir au moins 2 caractères.",
     *      maxMessage="Le titre du livre ne peut pas dépasser 100 caractères."
     * )
     * @Groups({"listAuteurFull","listEditeurFull","listGenreFull"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $titre;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Range(
     *      min=5,
     *      max=400,
     *      notInRangeMessage="Le prix doit être compris entre 5€ et 400€."
     * )
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_manager","put_admin"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min=1000,
     *      max=2100,
     *      notInRangeMessage="L'année de publication doit être comprise entre 1000 et l'année actuelle."
     * )
     * @Assert\LessThanOrEqual(
     *      value=2025,
     *      message="L'année de publication ne peut pas dépasser l'année en cours."
     * )
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Groups({"get_role_adherent","put_manager"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="livre")
     * @Groups({"listPretFull", "listPretSimple"})
     * @ApiSubresource
     * @Groups({"get_role_manager"})
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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    /**
    * @Groups({"listLivreFull"})
    */
    public function getGenre(): ?Genre
    {
        return $this->genre;
    }


    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    /**
    * @Groups({"listLivreFull"})
    */
    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }


    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;
        return $this;
    }

    /**
    * @Groups({"listLivreFull"})
    */
    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }


    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;
        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(?string $langue): self
    {
        $this->langue = $langue;
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
            $pret->setLivre($this);
        }
        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }
        return $this;
    }
}