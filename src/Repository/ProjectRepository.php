<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findAll(array $orderBy = null)
    {
        return $this->findBy([], $orderBy);
    }

    public function findSkills(array $search): ?array
    {
        $queryBuilder = $this->createQueryBuilder('p')
        ->select('p', 's')
        ->join('p.skills', 's');

        if (!empty($search['skills'])) {
            $queryBuilder = $queryBuilder
                ->andWhere(':skills MEMBER OF p.skills')
                ->setParameter('skills', $search['skills']);
        }

        $queryBuilder = $queryBuilder
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();
        return $queryBuilder->getResult();
    }
}
