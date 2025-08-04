<?php

namespace Animalmanager\Grid\Factory;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Grid;
use PrestaShop\PrestaShop\Core\Grid\GridInterface;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\GridDefinitionFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use Animalmanager\Grid\Data\AnimalBreedGridDataFactory;

final class AnimalBreedGridFactory
{
    private GridDefinitionFactoryInterface $definitionFactory;
    private AnimalBreedGridDataFactory $dataFactory;
    private FormFactoryInterface $formFactory;

    public function __construct(
        GridDefinitionFactoryInterface $definitionFactory,
        AnimalBreedGridDataFactory $dataFactory,
        FormFactoryInterface $formFactory
    ) {
        $this->definitionFactory = $definitionFactory;
        $this->dataFactory = $dataFactory;
        $this->formFactory = $formFactory;
    }

    public function getGrid(SearchCriteriaInterface $criteria): GridInterface
    {
        $definition = $this->definitionFactory->getDefinition();
        $data = $this->dataFactory->getData($criteria);

        $form = $this->formFactory
            ->createBuilder()
            ->add('_dummy', HiddenType::class, ['mapped' => false])
            ->getForm();

        $form->submit([], false);

        return new Grid(
            $definition,
            $data,
            $criteria,
            $form
        );
    }
}
