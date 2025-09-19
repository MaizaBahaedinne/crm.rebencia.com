# Structure des Estimations Immobilières

## 📁 Organisation des contrôleurs et vues

### Contrôleurs

#### 1. `Estimation.php` - Création d'estimations
- **Rôle** : Formulaire de création et calcul d'estimations
- **Routes** :
  - `/estimation` → Formulaire wizard de création
  - `/estimation/calcul` → Traitement et calcul
  - `/estimation/resultat/:id` → Affichage du résultat
- **Vues utilisées** : `estimation/`
  - `form_wizard.php` - Formulaire de création
  - `result.php` - Affichage résultat
  - `result_premium.php` - Version premium du résultat

#### 2. `Estimations.php` - Gestion d'estimations  
- **Rôle** : Liste, filtrage et gestion des estimations existantes
- **Routes** :
  - `/estimations` → Liste des estimations avec filtres
  - `/estimations/view/:id` → Détail d'une estimation
  - `/estimations/edit/:id` → Modification
  - `/estimations/filter` → Filtrage AJAX
- **Vues utilisées** : `estimations/`
  - `index.php` - Interface principale de gestion
  - `view.php` - Détail d'une estimation

### Dossiers de vues

#### `estimation/` - Création d'estimations
```
estimation/
├── form_wizard.php      # Formulaire de création
├── result.php           # Résultat basique  
├── result_premium.php   # Résultat premium
├── list.php            # Liste simple
├── list_premium.php    # Liste premium
└── zones_*.php         # Gestion des zones
```

#### `estimations/` - Gestion d'estimations
```
estimations/
├── index.php           # Interface principale (moderne avec cartes/filtres)
└── view.php            # Détail d'une estimation
```

## 🔧 Fonctionnalités par rôle

### Agent
- **Création** : Accès complet au formulaire (`/estimation`)
- **Gestion** : Voit uniquement ses estimations (`/estimations`)
- **Filtres** : Statut, type, période

### Manager  
- **Création** : Accès complet 
- **Gestion** : Voit les estimations de son agence
- **Filtres** : + sélection d'agents de l'agence

### Admin
- **Création** : Accès complet
- **Gestion** : Voit toutes les estimations
- **Filtres** : + sélection d'agences et agents

## 📋 URLs principales

- **Créer** : `https://crm.rebencia.com/estimation`
- **Gérer** : `https://crm.rebencia.com/estimations`

## 🗃️ Base de données

- **Table principale** : `crm_properties`
- **Modèle** : `Estimation_model.php`
- **Champs clés** : 
  - `agent_id` (filtrage par rôle)
  - `statut_dossier` (workflow)
  - `valeur_estimee` (prix)
  - `zone_id` (géolocalisation)

---
**Dernière mise à jour** : 19 septembre 2025
**Nettoyage effectué** : Suppression des doublons et vues obsolètes
