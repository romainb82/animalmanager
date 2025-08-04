<?php

namespace Animalmanager\Grid\Data;

use Animalmanager\Grid\Query\AnimalTypeQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class AnimalTypeGridDataFactory
{
    private $queryBuilder;

    public function __construct(AnimalTypeQueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getData(SearchCriteriaInterface $searchCriteria): GridData
    {
        $searchQueryBuilder = $this->queryBuilder->getSearchQueryBuilder($searchCriteria);
        $countQueryBuilder = $this->queryBuilder->getCountQueryBuilder($searchCriteria);

        $records = $searchQueryBuilder->execute()->fetchAllAssociative();
        $count = (int) $countQueryBuilder->execute()->fetchOne();

        return new GridData(
            new RecordCollection($records), 
            $count
        );
    }
}
