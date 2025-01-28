<?php

namespace App\Entity;

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
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $datePret;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $dateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $adherent;

    /**
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $nomAdherent;

    /**
     * @Groups({"listPretFull", "listPretSimple"})
     */
    private $prenomAdherent;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @Groups({"listLivreFull", "listLivreSimple"})
     */
    private $livre;

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

    public function getDateRetourReelle(): ?\DateTimeInterface
    {
        return $this->dateRetourReelle;
    }

    public function setDateRetourReelle(\DateTimeInterface $dateRetourReelle): self
    {
        $this->dateRetourReelle = $dateRetourReelle;

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

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get the value of nomAdherent
     */ 
    public function getNomAdherent()
    {
        return $this->nomAdherent;
    }

    /**
     * Set the value of nomAdherent
     *
     * @return  self
     */ 
    public function setNomAdherent($nomAdherent)
    {
        $this->nomAdherent = $nomAdherent;

        return $this;
    }

    /**
     * Get the value of prenomAdherent
     */ 
    public function getPrenomAdherent()
    {
        return $this->prenomAdherent;
    }

    /**
     * Set the value of prenomAdherent
     *
     * @return  self
     */ 
    public function setPrenomAdherent($prenomAdherent)
    {
        $this->prenomAdherent = $prenomAdherent;

        return $this;
    }
}
