<?php

namespace App\Entity;

use App\Entity\Livre;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Il existe déja un genre avec le libellé {{ value }}, veuillez saisir un autre libellé."
 * )
 * @ApiResource(
 *     itemOperations={
 *         "get_simple"={
 *             "method"="GET",
 *             "path"="/genres/{id}/simple",
 *             "normalization_context"={"groups"={"listGenreSimple"}}
 *         },
 *          "get_full"={
 *             "method"="GET",
 *             "path"="/genres/{id}/full",
 *             "normalization_context"={"groups"={"listGenreFull"}}
 *         },
 *         "post"={
 *             "method"="POST",
 *             "path"="/genres/{id}",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"post_role_manager"}}
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/genres/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_GENRE') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"get_role_genre"}}
 *         },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/genres/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_GENRE') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"put_role_admin"}}
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/genres/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_GENRE') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"delete_role_admin"}}
 *         },
 *         "patch"={
 *             "method"="PATCH",
 *             "path"="/genres/{id}",
 *             "access_control"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_GENRE') and object == user)",
 *             "access_control_message"="Vous n'avez pas les droits d'accès.",
 *             "normalization_context"={"groups"={"patch_role_admin"}}
 *         },
 *     }
 * )
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listGenreSimple","listGenreFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listLivreFull"})
     * @Assert\Length(
     *      min=2,
     *      max=50,
     *      minMessage="Le libellé doit contenir au moins {{ limit }} caractères",
     *      maxMessage="Le libellé doit contenir au plus {{ limit }} caractères "
     * )
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="genre")
     * @Groups({"listGenreSimple","listGenreFull"})
     */
    private $livres;

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
     * @Groups({"listGenreFull"})
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }


    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setGenre($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getGenre() === $this) {
                $livre->setGenre(null);
            }
        }

        return $this;
    }
}
