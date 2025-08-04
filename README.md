# 🐾 AnimalManager - Module de gestion des types et races d'animaux

AnimalManager est un module PrestaShop 8.2+ développé pour permettre une gestion simple et efficace des **types d’animaux** (Chien, Chat, etc.) et de leurs **races** associées directement depuis le back-office, via l’interface Symfony moderne.

## ✨ Fonctionnalités

### 🔧 Configuration du module
- Ajout, édition et suppression de **types d’animaux** (ex : Chat, Chien).
- Gestion via **interface Grid Symfony** : propre, performante et conforme aux standards PrestaShop.
- Grille interactive avec :
  - Colonnes dynamiques
  - Actions individuelles (✏️ modifier, 🗑️ supprimer)
  - Suppression multiple

### 🧬 Gestion des races
- Ajout/édition de **races** liées à un type d’animal
- Toggle d’activation / désactivation
- Suppression groupée
- Interface 100% Symfony Grid avec liens, boutons, confirmation, sécurité CSRF

---

## 🗂️ Structure

- **src/**
  - `Controller/Admin/` → Contrôleurs Symfony (FrameworkBundleAdminController)
  - `Grid/` → Composants Grid (DefinitionFactory, Factory, QueryBuilder, DataFactory)
- **views/templates/admin/** → Templates Twig modernes
- **config/services.yml** → Injection de dépendances Grid
- **translations/** → Dossier prêt à accueillir vos fichiers de traduction

---

## 🚀 Installation

1. Déposez le module dans le dossier `/modules/animalmanager`
2. Activez-le depuis le back-office
3. Accédez à la configuration via **Modules > AnimalManager > Configuration**

---

## 🛡️ Sécurité & Conformité

- Méthodes HTTP respectées (POST pour delete)
- Pas d’override, pas de legacy : **architecture propre Symfony Grid**

---

## 📷 Aperçu

```text
Configuration
├── Types d’animaux (ex : Chien, Chat, Reptile)
│   ├── Ajouter ✚
│   ├── Modifier ✏️
│   └── Supprimer 🗑️ (ou en masse)
└── Races associées
    ├── Liste avec statut ✅ / ❌
    └── Actions CRUD
