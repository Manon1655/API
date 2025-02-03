<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EditeurRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EditeurRepository::class)
 * @ApiResource()
 * @UniqueEntity(fields={"nom"}, message="Le nom de l'éditeur doit être unique.")
 */
class Editeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listEditeurFull","listEditeurSimple",})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Le nom de l'éditeur ne peut pas être vide.")
     * @Assert\Length(
     *      min=4,
     *      max=50,
     *      minMessage="Le nom de l'éditeur doit contenir au moins 4 caractères.",
     *      maxMessage="Le nom de l'éditeur ne peut pas dépasser 50 caractères."
     * )
     * @Groups({"listEditeurFull","listEditeurSimple",})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="editeur")
     * @Groups({"listEditeurFull","listEditeurSimple",})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $livres;

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

    /**
     * @Groups({"listEditeurFull"})
     */
    public function getLivres(): array
    {
        return $this->livres->map(function (Livre $livre) {
            return $livre->getTitre();
        })->toArray();
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setEditeur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getEditeur() === $this) {
                $livre->setEditeur(null);
            }
        }

        return $this;
    }
}
