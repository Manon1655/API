<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Nationalite;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 * @ApiResource()
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères."
     * )
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $livres;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class, inversedBy="auteurs")
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
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
    public function getLivres(): array
    {
        return $this->livres->map(function (Livre $livre) {
            return $livre->getTitre();
        })->toArray();
    }

    /**
     * @Groups({"listAuteurFull"})
     */
    public function getNationalite(): ?string
    {
        return $this->nationalite ? $this->nationalite->getLibelle() : null;
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

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }
}
