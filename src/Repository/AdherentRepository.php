<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherent>
 *
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    /**
     * @param Adherent $entity
     * @param boolean $flush
     * @return void
     */
    public function nbPretsParAdherent()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a.id,a.nom,a.prenom, count(p.id) as nbPrets')
            ->join('a.prets', 'p')
            ->groupBy('a')
            ->orderBy('nbPrets', 'DESC');
        return $query->getQuery()->getResult();
    }
    
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Adherent $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Adherent $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
