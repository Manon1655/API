<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
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
     * @Groups({"listPretFull","listPretSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull","listPretSimple"})
     */
    private $datePret;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull","listPretSimple"})
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull","listPretSimple"})
     */
    private $dateRetourRelle;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @Groups({"listAdherentFull", "listAdherentSimple"})
     */
    private $adh;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
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

    public function getAdh(): ?Adherent
    {
        return $this->adh;
    }

    public function setAdh(?Adherent $adh): self
    {
        $this->adh = $adh;

        return $this;
    }
}
