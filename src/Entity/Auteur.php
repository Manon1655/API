<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listGenreFull", "listAuteurFull", "listAuteurSimple"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listGenreFull", "listAuteurFull", "listAuteurSimple"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     * @Groups({"listAuteurFull"})
     */
    private $livres;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class, inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listGenreFull", "listAuteurFull", "listAuteurSimple"})
     * @Assert\NotNull(message="La nationalité est obligatoire.")
     */
    private $Relation;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    // Getters et Setters

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
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
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

    public function getRelation(): ?Nationalite
    {
        return $this->Relation;
    }

    public function setNationalite(?Nationalite $Relation): self
    {
        $this->Relation = $Relation;

        return $this;
    }
}
