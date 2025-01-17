<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivreRepository")
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listLivreFull", "listLivreSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Assert\NotBlank(message="L'ISBN ne peut pas être vide.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="L'ISBN ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listLivreFull", "listLivreSimple"})
     * @Assert\NotBlank(message="Le titre est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le titre ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"listLivreFull"})
     * @Assert\PositiveOrZero(message="Le prix doit être un nombre positif ou nul.")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genre", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editeur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auteur", inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listLivreFull", "listLivreSimple"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"listLivreFull"})
     * @Assert\Range(
     *     min=1000,
     *     max=9999,
     *     notInRangeMessage="L'année doit être comprise entre {{ min }} et {{ max }}."
     * )
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listLivreFull"})
     * @Assert\Length(
     *     max=255,
     *     maxMessage="La langue ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $langue;

    // --- Getters and Setters ---

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
}
