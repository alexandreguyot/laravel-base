<?php

/*
|--------------------------------------------------------------------------
| Feature Flags — Configuration par défaut
|--------------------------------------------------------------------------
|
| Chaque clé correspond à un module ou une sous-fonctionnalité.
| Ces valeurs sont les défauts (config dev/déploiement).
| Elles sont surchargées par la base de données via l'UI admin.
|
| Format :
|   'cle' => [
|       'label'       => 'Nom affiché dans l\'UI',
|       'description' => 'Description courte',
|       'enabled'     => true|false,
|       'group'       => 'Groupe dans l\'UI',
|   ]
|
*/

return [

    // ----------------------------------------------------------------
    // Gestion des utilisateurs
    // ----------------------------------------------------------------
    'user_management' => [
        'label'       => 'Gestion des utilisateurs',
        'description' => 'Module complet : permissions, rôles, utilisateurs, audit logs',
        'enabled'     => true,
        'group'       => 'Administration',
    ],
    'audit_logs' => [
        'label'       => 'Audit logs',
        'description' => 'Historique des actions utilisateurs',
        'enabled'     => true,
        'group'       => 'Administration',
    ],

    // ----------------------------------------------------------------
    // Notifications
    // ----------------------------------------------------------------
    'user_alerts' => [
        'label'       => 'Alertes utilisateur',
        'description' => 'Système de notifications in-app',
        'enabled'     => true,
        'group'       => 'Communication',
    ],
    'messages' => [
        'label'       => 'Messagerie interne',
        'description' => 'Conversations et messages entre utilisateurs',
        'enabled'     => true,
        'group'       => 'Communication',
    ],

    // ----------------------------------------------------------------
    // Produits
    // ----------------------------------------------------------------
    'product_management' => [
        'label'       => 'Gestion des produits',
        'description' => 'Catégories, tags et produits',
        'enabled'     => true,
        'group'       => 'E-commerce',
    ],
    'product_export' => [
        'label'       => 'Export produits (Excel/CSV)',
        'description' => 'Boutons d\'export dans les listes de produits',
        'enabled'     => true,
        'group'       => 'E-commerce',
    ],
    'product_import' => [
        'label'       => 'Import produits (CSV)',
        'description' => 'Import en masse via CSV',
        'enabled'     => true,
        'group'       => 'E-commerce',
    ],

    // ----------------------------------------------------------------
    // CRM
    // ----------------------------------------------------------------
    'crm' => [
        'label'       => 'CRM',
        'description' => 'Clients, statuts, notes, documents',
        'enabled'     => true,
        'group'       => 'CRM',
    ],
    'crm_documents' => [
        'label'       => 'Documents CRM',
        'description' => 'Pièces jointes et documents clients',
        'enabled'     => true,
        'group'       => 'CRM',
    ],

    // ----------------------------------------------------------------
    // Tâches
    // ----------------------------------------------------------------
    'task_management' => [
        'label'       => 'Gestion des tâches',
        'description' => 'Tâches, statuts, tags, calendrier',
        'enabled'     => true,
        'group'       => 'Productivité',
    ],
    'task_calendar' => [
        'label'       => 'Calendrier des tâches',
        'description' => 'Vue calendrier pour les tâches',
        'enabled'     => true,
        'group'       => 'Productivité',
    ],
    'system_calendar' => [
        'label'       => 'Calendrier système',
        'description' => 'Calendrier global partagé',
        'enabled'     => true,
        'group'       => 'Productivité',
    ],

    // ----------------------------------------------------------------
    // Contenu
    // ----------------------------------------------------------------
    'content_management' => [
        'label'       => 'Gestion du contenu',
        'description' => 'Pages, catégories et tags éditoriaux',
        'enabled'     => true,
        'group'       => 'Contenu',
    ],

    // ----------------------------------------------------------------
    // FAQ
    // ----------------------------------------------------------------
    'faq' => [
        'label'       => 'FAQ',
        'description' => 'Questions fréquentes et catégories',
        'enabled'     => true,
        'group'       => 'Contenu',
    ],

    // ----------------------------------------------------------------
    // Contacts
    // ----------------------------------------------------------------
    'contact_management' => [
        'label'       => 'Gestion des contacts',
        'description' => 'Entreprises et contacts',
        'enabled'     => true,
        'group'       => 'Contacts',
    ],

    // ----------------------------------------------------------------
    // LMS (Learning Management System)
    // ----------------------------------------------------------------
    'lms' => [
        'label'       => 'LMS — Formations',
        'description' => 'Cours, leçons, tests, questions et résultats',
        'enabled'     => true,
        'group'       => 'Formation',
    ],
    'lms_courses' => [
        'label'       => 'Cours',
        'description' => 'Module cours du LMS',
        'enabled'     => true,
        'group'       => 'Formation',
    ],
    'lms_tests' => [
        'label'       => 'Tests & Évaluations',
        'description' => 'Tests, questions, options et résultats',
        'enabled'     => true,
        'group'       => 'Formation',
    ],

    // ----------------------------------------------------------------
    // Fonctionnalités transversales
    // ----------------------------------------------------------------
    'excel_export' => [
        'label'       => 'Export Excel global',
        'description' => 'Boutons d\'export Excel dans tous les modules',
        'enabled'     => true,
        'group'       => 'Transversal',
    ],
    'csv_import' => [
        'label'       => 'Import CSV global',
        'description' => 'Import de données via CSV',
        'enabled'     => true,
        'group'       => 'Transversal',
    ],
    'global_search' => [
        'label'       => 'Recherche globale',
        'description' => 'Barre de recherche dans la navbar',
        'enabled'     => true,
        'group'       => 'Transversal',
    ],
    'language_switcher' => [
        'label'       => 'Sélecteur de langue',
        'description' => 'Permet de changer la langue de l\'interface',
        'enabled'     => true,
        'group'       => 'Transversal',
    ],

];
