<?php

namespace Animalmanager\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\EditRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\DeleteRowAction;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;


final class AnimalBreedGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    protected $translator;

    public function __construct(
        TranslatorInterface $translator,
        HookDispatcherInterface $hookDispatcher
    ) {
        parent::__construct($hookDispatcher);
        $this->translator = $translator;
    }

    protected function getId(): string
    {
        return 'animal_breed';
    }

    protected function getName(): string
    {
        return $this->translator->trans('Animal Breeds', [], 'Modules.Animalmanager.Admin');
    }

    protected function getColumns(): ColumnCollection
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_animal_breed'))
                    ->setName('ID')
                    ->setOptions(['field' => 'id_animal_breed'])
            )
            ->add(
                (new DataColumn('name'))
                    ->setName('Name')
                    ->setOptions(['field' => 'name'])
            )
            ->add(
                (new ToggleColumn('active'))
                    ->setName('Active')
                    ->setOptions([
                        'field' => 'active',
                        'primary_field' => 'id_animal_breed',
                        'route' => 'admin_animal_breed_toggle',
                        'route_param_name' => 'id',
                    ])
            )
            ->add(
                (new DataColumn('type_name'))
                    ->setName('Type')
                    ->setOptions(['field' => 'type_name'])
            );
    }

    protected function getBulkActions(): BulkActionCollection
    {
        return (new BulkActionCollection())
            ->add(
                (new SubmitBulkAction('bulk_delete'))
                    ->setName($this->translator->trans('Bulk Delete', [], 'Admin.Actions'))
                    ->setOptions([
                        'submit_route' => 'admin_animal_breed_bulk_delete',
                        'confirm_message' => $this->translator->trans(
                            'Are you sure you want to delete the selected breeds?',
                            [],
                            'Modules.Animalmanager.Admin'
                        ),
                    ])
            );
    }


    protected function getFilters(): FilterCollection
    {
        return new FilterCollection();
    }

    protected function getGridActions(): GridActionCollection
    {
        return (new GridActionCollection())
            ->add(new SimpleGridAction(
                'add',
                [
                    'name' => $this->translator->trans('Add new breed', [], 'Modules.Animalmanager.Admin'),
                    'icon' => 'add_circle_outline',
                    'route' => 'admin_animal_breed_add',
                ]
            ))
            ->add(new SimpleGridAction(
                'refresh',
                [
                    'name' => $this->translator->trans('Refresh', [], 'Admin.Actions'),
                    'icon' => 'refresh',
                    'route' => 'admin_animal_breed_index',
                ]
            ));
    }

    protected function getRowActions(): RowActionCollection
    {
        return (new RowActionCollection())
            ->add(
                (new EditRowAction('edit'))
                    ->setName($this->translator->trans('Edit', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'admin_animal_breed_edit',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id_animal_breed',
                    ])
            )
            ->add(
                (new DeleteRowAction('delete'))
                    ->setName($this->translator->trans('Delete', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'admin_animal_breed_delete',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id_animal_breed',
                        'confirm_message' => $this->translator->trans('Delete this breed?', [], 'Modules.Animalmanager.Admin'),
                    ])
            );
    }
}
