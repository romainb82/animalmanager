<?php

namespace Animalmanager\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PrestaShop\PrestaShop\Core\Grid\Presenter\GridPresenterInterface;
use Animalmanager\Grid\Factory\AnimalBreedGridFactory;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use Db;

/**
 * @Route("/module/animalmanager")
 */
class AdminAnimalBreedController extends FrameworkBundleAdminController
{
    private AnimalBreedGridFactory $gridFactory;
    private GridPresenterInterface $gridPresenter;


    public function __construct(
        AnimalBreedGridFactory $gridFactory,
        GridPresenterInterface $gridPresenter,
    ) {
        $this->gridFactory = $gridFactory;
        $this->gridPresenter = $gridPresenter;
    }

    /**
     * @Route("/animal-breed", name="admin_animal_breed_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $orderBy = $request->query->get('orderBy', 'id_animal_breed');
        $orderWay = $request->query->get('orderWay', 'asc');
        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 20);

        $criteria = new SearchCriteria(
            $request->query->all(),
            $orderBy,
            $orderWay,
            $offset,
            $limit
        );

        $grid = $this->gridFactory->getGrid($criteria);
        $renderedGrid = $this->gridPresenter->present($grid);

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_breed/index.html.twig', [
            'grid' => $renderedGrid,
        ]);
    }

    /**
     * @Route("/animal-breed/add", name="admin_animal_breed_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $types = Db::getInstance()->executeS('SELECT id_animal_type, name FROM ' . _DB_PREFIX_ . 'animal_type');

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $idAnimalType = (int) $request->request->get('id_animal_type');
            $acvtive = (int) $request->request->get('active');

            Db::getInstance()->insert('animal_breed', [
                'id_animal_type' => $idAnimalType,
                'name' => pSQL($name),
                'active' => $acvtive,
            ]);

            $this->addFlash('success', 'Race ajoutée avec succès.');
            return $this->redirectToRoute('admin_animal_breed_index');
        }

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_breed/add.html.twig', [
            'types' => $types,
        ]);
    }

    /**
     * @Route("/animal-breed/toggle/{id}", name="admin_animal_breed_toggle", methods={"POST"})
     */
    public function toggle(int $id): Response
    {
        $db = \Db::getInstance();
        $breed = $db->getRow('SELECT active FROM ' . _DB_PREFIX_ . 'animal_breed WHERE id_animal_breed = ' . (int) $id);

        if (!$breed) {
            $this->addFlash('error', 'Race introuvable.');
        } else {
            $newState = (int)!$breed['active'];
            $db->update('animal_breed', ['active' => $newState], 'id_animal_breed = ' . (int) $id);
            $this->addFlash('success', 'Statut mis à jour.');
        }

        return $this->redirectToRoute('admin_animal_breed_index');;
    }

    /**
     * @Route("/animal-breed/bulk-delete", name="admin_animal_breed_bulk_delete", methods={"POST"})
     */
    public function bulkDelete(Request $request): Response
    {
        $ids = $request->request->get('breed_ids', []);
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $idList = implode(',', array_map('intval', $ids));

        if (!empty($idList)) {
            \Db::getInstance()->delete('animal_breed', 'id_animal_breed IN (' . $idList . ')');
            $this->addFlash('success', 'Les races sélectionnées ont été supprimées.');
        } else {
            $this->addFlash('error', 'Aucune race sélectionnée.');
        }

        return $this->redirectToRoute('admin_animal_breed_index');
    }

    /**
     * @Route("/animal-breed/edit/{id}", name="admin_animal_breed_edit", methods={"GET", "POST"})
     */
    public function edit(int $id, Request $request): Response
    {
        $db = \Db::getInstance();
        $breed = $db->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'animal_breed WHERE id_animal_breed = ' . (int) $id);

        if (!$breed) {
            $this->addFlash('error', 'Race introuvable.');
            return $this->redirectToRoute('admin_animal_breed_index');
        }

        $types = $db->executeS('SELECT id_animal_type, name FROM ' . _DB_PREFIX_ . 'animal_type');

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $idAnimalType = (int) $request->request->get('id_animal_type');
            $active = (int) $request->request->get('active', 0);

            $db->update('animal_breed', [
                'name' => pSQL($name),
                'id_animal_type' => $idAnimalType,
                'active' => $active,
            ], 'id_animal_breed = ' . (int) $id);

            $this->addFlash('success', 'Race modifiée avec succès.');
            return $this->redirectToRoute('admin_animal_breed_index');
        }

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_breed/edit.html.twig', [
            'types' => $types,
            'breed' => $breed,
        ]);
    }

    /**
     * @Route("/animal-breed/delete/{id}", name="admin_animal_breed_delete", methods={"GET", "POST"})
     */
    public function delete(int $id): Response
    {
        \Db::getInstance()->delete('animal_breed', 'id_animal_breed = ' . (int) $id);

        $this->addFlash('success', 'Race supprimée avec succès.');
        return $this->redirectToRoute('admin_animal_breed_index');
    }
}
