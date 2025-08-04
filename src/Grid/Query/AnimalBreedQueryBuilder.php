<?php

namespace Animalmanager\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class AnimalBreedQueryBuilder extends AbstractDoctrineQueryBuilder
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('ab.id_animal_breed', 'ab.name', 'ab.active', 'at.name AS type_name')
            ->from(_DB_PREFIX_ . 'animal_breed', 'ab')
            ->leftJoin('ab', _DB_PREFIX_ . 'animal_type', 'at', 'ab.id_animal_type = at.id_animal_type');

        $filters = $searchCriteria->getFilters();
        if (isset($filters['name'])) {
            $qb->andWhere('ab.name LIKE :name')
               ->setParameter('name', '%' . $filters['name'] . '%');
        }

        $qb->setFirstResult($searchCriteria->getOffset());
        $qb->setMaxResults($searchCriteria->getLimit());

        if ($searchCriteria->getOrderBy()) {
            $qb->orderBy('ab.' . $searchCriteria->getOrderBy(), $searchCriteria->getOrderWay());
        }

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(ab.id_animal_breed)')
            ->from(_DB_PREFIX_ . 'animal_breed', 'ab')
            ->leftJoin('ab', _DB_PREFIX_ . 'animal_type', 'at', 'ab.id_animal_type = at.id_animal_type');

        $filters = $searchCriteria->getFilters();
        if (isset($filters['name'])) {
            $qb->andWhere('ab.name LIKE :name')
               ->setParameter('name', '%' . $filters['name'] . '%');
        }

        return $qb;
    }
}
