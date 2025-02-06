<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Adherent;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/prets/{id}",
 *              "security"="(is_granted('ROLE_PRET') and object.getPret() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message"="Vous ne pouvez avoir accès qu'à vos propres prêts."
 *           }
 *      }
 * )
 *  },
 *          "get_full"={
 *             "method"="GET",
 *             "path"="/prets/{id}/full",
 *             "normalization_context"={"groups"={"listPretFull"}}
 *         },
 *         "post"={
 *             "method"="POST",
 *             "path"="/prets/{id}",
 *             "security"="is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"post_role_manager"}}
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_PRET') and object == user)",
 *             "security_message"="Vous ne pouvez avoir accès qu'à vos propres prêts.",
 *             "normalization_context"={"groups"={"get_role_pret"}}
 *         },
 *         "get_full"={
 *             "method"="GET",
 *             "path"="/prets/{id}/full",
 *             "normalization_context"={"groups"={"listPretFull"}}
 *         },
 *         "put"={
 *             "method"="PUT",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_PRET') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"put_role_admin"}}
 *         },
 *         "patch"={
 *             "method"="PATCH",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_PRET') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *             "denormalization_context"={"groups"={"patch_role_admin"}}
 *         },
 *         "delete"={
 *             "method"="DELETE",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_MANAGER') or is_granted('ROLE_PRET') and object == user)",
 *             "security_message"="Vous n'avez pas les droits d'accès.",
 *              "normalization_context"={"groups"={"delete_role_admin"}}
 *         }
 *     }
 * )
 * @HasLifecycleCallbacks()
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listPretFull", "listPretSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     * @Assert\NotBlank(message="La date de prêt est obligatoire.")
     * @Assert\GreaterThanOrEqual(
     *     "today",
     *     message="La date de prêt ne peut pas être antérieure à aujourd'hui."
     * )
     */
    private $datePret;

    /**
     * @ORM\Column(type="date")
     * @Groups({"listPretFull", "listPretSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     * @Assert\NotBlank(message="La date de retour prévue est obligatoire.")
     * @Assert\GreaterThan(
     *     propertyPath="datePret",
     *     message="La date de retour prévue doit être postérieure à la date de prêt."
     * )
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"listPretFull", "listPretSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     * @Assert\GreaterThan(
     *     propertyPath="datePret",
     *     message="La date de retour réelle doit être postérieure à la date de prêt."
     * )
     */
    private $dateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @Groups({"listPretFull", "listPretSimple"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $adherent;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @Groups({"listLivreFull"})
     * @Groups({"post_role_manager","put_role_admin"})
     */
    private $livre;

    public function __construct()
    {
        $this->datePret = new \DateTime();
        $DateRetourPrevue=date('Y-m-d H:m:n',strtotime('15 days',$this->getDatePret()->getTimestamp()));
        $DateRetourPrevue=\DateTime::createFromFormat('Y-m-d H:m:n',$DateRetourPrevue);
        $this->dateRetourPrevue=$DateRetourPrevue;
        $this->dateRetourReelle= null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret($datePret): self
{
    $this->datePret = $datePret;
    if ($this->dateRetourPrevue === null) {
        $this->dateRetourPrevue = (clone $datePret)->modify('+15 days');
    }

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

    /**
     * Si la date de retour réelle est fournie, vérifie qu'elle est après la date de prêt.
     * 
     * @param \DateTime|null $dateRetourReelle
     * @return self
     */
    public function setDateRetourReelle(?\DateTimeInterface $dateRetourReelle): self
    {
        if ($dateRetourReelle !== null && $dateRetourReelle < $this->datePret) {
            throw new \Exception("La date de retour réelle ne peut pas être antérieure à la date de prêt.");
        }

        $this->dateRetourReelle = $dateRetourReelle;

        return $this;
    }

    /**
     * @Groups({"listPretFull", "listPretSimple"})
     */
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
     * @ORM\PrePersist
     *
     * @return void
     */
    public function RendIndDispoLivre()
    {
        $this->getLivre()->setDispo(false);
    }
        
}