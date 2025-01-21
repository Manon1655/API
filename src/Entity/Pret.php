<?php

namespace App\Entity;

use App\Entity\Livre;
use DateTimeInterface;
use App\Entity\Adherent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listPretFull", "listPretSimple", "listLivreFull", "listLivreSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple", "listLivreFull", "listLivreSimple"})
     */
    private $datePret;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple", "listLivreFull", "listLivreSimple"})
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"listPretFull", "listPretSimple", "listLivreFull", "listLivreSimple"})
     */
    private $dateRetourRelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $adherent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourRelle(): ?\DateTimeInterface
    {
        return $this->dateRetourRelle;
    }

    public function setDateRetourRelle(\DateTimeInterface $dateRetourRelle): self
    {
        $this->dateRetourRelle = $dateRetourRelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}