<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NationaliteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=NationaliteRepository::class)
 * @UniqueEntity(fields={"libelle"}, message="Le libellé de la nationalité doit être unique.")
 * @ApiResource()
 */
class Nationalite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups({"listNationaliteFull","listNationaliteFull"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Le libellé de la nationalité ne peut pas être vide.")
     * @Assert\Length(
     *      min=4,
     *      max=50,
     *      minMessage="Le libellé de la nationalité doit contenir au moins 4 caractères.",
     *      maxMessage="Le libellé de la nationalité ne peut pas dépasser 50 caractères."
     * )
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Auteur::class, mappedBy="nationalite")
     * @Groups({"listNationaliteFull","listNationaliteFull"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $auteurs;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return array
     * @Groups({"listNationaliteFull"})
     */
    public function getAuteurs(): array
    {
        return $this->auteurs->map(function(Auteur $auteur) {
            return [
                'nom' => $auteur->getNom(),
                'prenom' => $auteur->getPrenom()
            ];
        })->toArray();
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
            $auteur->setNationalite($this);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->removeElement($auteur)) {
            if ($auteur->getNationalite() === $this) {
                $auteur->setNationalite(null);
            }
        }

        return $this;
    }
}
