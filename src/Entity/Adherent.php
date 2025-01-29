<?php

namespace App\Entity;

use App\Entity\Pret;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
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
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="Le prénom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="L'adresse est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="L'adresse ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le code postal est obligatoire.")
     * @Assert\Length(
     *    min=5,
     *    minMessage="Le code postal doit contenir {{ limit }} caractères."
     * )
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="La ville est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="La ville ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="Le téléphone est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="Le téléphone ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     * @Assert\NotBlank(message="L'email est obligatoire.")
     * @Assert\Length(
     *    max=255,
     *    maxMessage="L'email ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="adherent")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
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

    /**
     * @return Collection<int, \DateTimeInterface>
     */
    public function getPrets(): Collection
    {
        return $this->prets->map(function (Pret $pret) {
            return $pret->getDatePret();
        });
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
            // set the owning side to null (unless already changed)
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }

        return $this;
    }
}
