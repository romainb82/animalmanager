<?php

namespace Animalmanager\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class AnimalTypeQueryBuilder extends AbstractDoctrineQueryBuilder
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $criteria): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('id_animal_type', 'name')
            ->from(_DB_PREFIX_ . 'animal_type');

        $qb->setFirstResult($criteria->getOffset());
        $qb->setMaxResults($criteria->getLimit());

        if ($criteria->getOrderBy()) {
            $qb->orderBy($criteria->getOrderBy(), $criteria->getOrderWay());
        }

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $criteria): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('COUNT(id_animal_type)')->from(_DB_PREFIX_ . 'animal_type');
        return $qb;
    }
}
