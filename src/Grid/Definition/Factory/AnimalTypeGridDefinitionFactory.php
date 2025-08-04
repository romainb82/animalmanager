<?php

namespace Animalmanager\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\EditRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\DeleteRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use Symfony\Contracts\Translation\TranslatorInterface;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;

final class AnimalTypeGridDefinitionFactory extends AbstractGridDefinitionFactory
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
        return 'animal_type';
    }

    protected function getName(): string
    {
        return $this->translator->trans('Animal Types', [], 'Modules.Animalmanager.Admin');
    }

    protected function getColumns(): ColumnCollection
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_animal_type'))
                ->setName('ID')
                ->setOptions(['field' => 'id_animal_type'])
            )
            ->add((new DataColumn('name'))
                ->setName('Name')
                ->setOptions(['field' => 'name'])
            );
    }

    protected function getFilters(): FilterCollection
    {
        return new FilterCollection();
    }

    protected function getGridActions(): GridActionCollection
    {
        return (new GridActionCollection())
            ->add(new SimpleGridAction('add', [
                'name' => $this->translator->trans('Add new type', [], 'Modules.Animalmanager.Admin'),
                'icon' => 'add_circle_outline',
                'route' => 'admin_animal_type_add',
            ]))
            ->add(new SimpleGridAction('refresh', [
                'name' => $this->translator->trans('Refresh', [], 'Admin.Actions'),
                'icon' => 'refresh',
                'route' => 'admin_animal_type_index',
            ]));
    }

    protected function getRowActions(): RowActionCollection
    {
        return (new RowActionCollection())
            ->add((new EditRowAction('edit'))
                ->setName($this->translator->trans('Edit', [], 'Admin.Actions'))
                ->setOptions([
                    'route' => 'admin_animal_type_edit',
                    'route_param_name' => 'id',
                    'route_param_field' => 'id_animal_type',
                ])
            )
            ->add((new DeleteRowAction('delete'))
                ->setName($this->translator->trans('Delete', [], 'Admin.Actions'))
                ->setOptions([
                    'route' => 'admin_animal_type_delete',
                    'route_param_name' => 'id',
                    'route_param_field' => 'id_animal_type',
                    'confirm_message' => $this->translator->trans('Delete this type?', [], 'Modules.Animalmanager.Admin'),
                ])
            );
    }
}