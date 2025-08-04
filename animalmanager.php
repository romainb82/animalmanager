<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Animalmanager extends Module
{
    public function __construct()
    {
        $this->name = 'animalmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Romain BESSEDE';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Animal Manager', [], 'Modules.Animalmanager.Admin', []);
        $this->description = $this->trans('Gère les types d\'animaux et leurs races.', [], 'Modules.Animalmanager.Admin');
        $this->confirmUninstall = $this->trans('Êtes-vous sûr de vouloir désinstaller ce module ?', [], 'Modules.Animalmanager.Admin');

        $this->controllers = ['adminanimalbreed'];
    }

    public function install()
    {
        return parent::install()
            && $this->registerTab()
            && $this->installDatabase();
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->unregisterTab()
            && $this->uninstallDatabase();
    }

    protected function registerTab()
    {
        // Créer manuellement le parent s’il n’existe pas
        $parentClass = 'AdminAnimalManager';
        $idParent = (int) Tab::getIdFromClassName($parentClass);
        if (!$idParent) {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->class_name = $parentClass;
            $parentTab->module = $this->name;
            $parentTab->id_parent = (int) Tab::getIdFromClassName('CONFIGURE');
            foreach (Language::getLanguages(true) as $lang) {
                $parentTab->name[$lang['id_lang']] = 'Animal Manager';
            }
            $parentTab->add();
        }

        // Enregistrer les sous-onglets
        $tabs = [
            [
                'class_name' => 'AdminAnimalType',
                'route_name' => 'admin_animal_type_index',
                'name' => 'Types d\'animaux',
                'parent_class_name' => 'AdminAnimalManager',
            ],
            [
                'class_name' => 'AdminAnimalBreed',
                'route_name' => 'admin_animal_breed_index',
                'name' => 'Gestion des races',
                'parent_class_name' => 'AdminAnimalManager',
            ],
        ];

        foreach ($tabs as $tabData) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $tabData['class_name'];
            $tab->module = $this->name;
            $tab->route_name = $tabData['route_name'];
            $tab->id_parent = (int) Tab::getIdFromClassName($tabData['parent_class_name']);

            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $tabData['name'];
            }

            $tab->add();
        }

        return true;
    }

    protected function unregisterTab()
    {
        $classes = ['AdminAnimalType', 'AdminAnimalBreed', 'AdminAnimalManager'];

        foreach ($classes as $class) {
            $idTab = (int) Tab::getIdFromClassName($class);
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }

        return true;
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new \Animalmanager\DependencyInjection\AnimalmanagerExtension();
        }
        return $this->extension;
    }


    protected function installDatabase()
    {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'animal_type` (
                `id_animal_type` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(64) NOT NULL
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4
        ') && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'animal_breed` (
                `id_animal_breed` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `id_animal_type` INT UNSIGNED NOT NULL,
                `name` VARCHAR(64) NOT NULL,
                `active` TINYINT(1) NOT NULL DEFAULT 1,
                FOREIGN KEY (`id_animal_type`) REFERENCES `' . _DB_PREFIX_ . 'animal_type`(`id_animal_type`) ON DELETE CASCADE
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4
        ');
    }

    protected function uninstallDatabase()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'animal_breed`')
            && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'animal_type`');
    }
}
