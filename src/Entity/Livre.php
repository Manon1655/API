<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listLivreFull","listLivreSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listLivreFull","listLivreSimple"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listLivreFull","listLivreSimple"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"listLivreFull","listLivreSimple"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listAuteurFull"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listGenreFull","listAuteurFull"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listGenreFull"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"listGenreFull","listAuteurFull"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listGenreFull","listAuteurFull"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="livre")
     * @Groups({"listLivreFull","listLivreSimple"})
     */
    private $pret;

    public function __construct()
    {
        $this->pret = new ArrayCollection();
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

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

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
    public function getPret(): Collection
    {
        return $this->pret;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->pret->contains($pret)) {
            $this->pret[] = $pret;
            $pret->setLivre($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->pret->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }

        return $this;
    }
}
