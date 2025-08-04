<?php

namespace Animalmanager\Controller\Admin;

use Animalmanager\Grid\Factory\AnimalTypeGridFactory;
use PrestaShop\PrestaShop\Core\Grid\Presenter\GridPresenterInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use Db;

/**
 * @Route("/module/animalmanager")
 */
class AdminAnimalTypeController extends FrameworkBundleAdminController
{
    private AnimalTypeGridFactory $gridFactory;
    private GridPresenterInterface $gridPresenter;

    public function __construct(
        AnimalTypeGridFactory $gridFactory,
        GridPresenterInterface $gridPresenter
    ) {
        $this->gridFactory = $gridFactory;
        $this->gridPresenter = $gridPresenter;
    }

    /**
     * @Route("/animal-type", name="admin_animal_type_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $orderBy = $request->query->get('orderBy', 'id_animal_type');
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

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_types/index.html.twig', [
            'grid' => $renderedGrid,
        ]);
    }

    /**
     * @Route("/animal-type/add", name="admin_animal_type_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');

            if (!empty($name)) {
                Db::getInstance()->insert('animal_type', [
                    'name' => pSQL($name),
                ]);
                $this->addFlash('success', 'Type d\'animal ajouté avec succès.');
                return $this->redirectToRoute('admin_animal_type_index');
            }

            $this->addFlash('error', 'Le nom est requis.');
        }

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_types/add.html.twig');
    }

    /**
     * @Route("/animal-type/edit/{id}", name="admin_animal_type_edit", methods={"GET", "POST"})
     */
    public function edit(int $id, Request $request): Response
    {
        $type = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'animal_type WHERE id_animal_type = ' . (int) $id);

        if (!$type) {
            $this->addFlash('error', 'Type non trouvé.');
            return $this->redirectToRoute('admin_animal_type_index');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');

            if (!empty($name)) {
                Db::getInstance()->update('animal_type', ['name' => pSQL($name)], 'id_animal_type = ' . (int) $id);
                $this->addFlash('success', 'Type d\'animal modifié avec succès.');
                return $this->redirectToRoute('admin_animal_type_index');
            }

            $this->addFlash('error', 'Le nom est requis.');
        }

        return $this->render('@Modules/animalmanager/views/templates/admin/animal_types/edit.html.twig', [
            'type' => $type,
        ]);
    }

    /**
     * @Route("/delete-type/{id}", name="admin_animal_type_delete_type", methods={"GET", "POST"})
     */
    public function delete(int $id): Response
    {
        $deleted = Db::getInstance()->delete('animal_type', 'id_animal_type = ' . (int) $id);

        if ($deleted) {
            $this->addFlash('success', 'Type supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression.');
        }

        return $this->redirectToRoute('admin_animal_type_index');
    }
}
