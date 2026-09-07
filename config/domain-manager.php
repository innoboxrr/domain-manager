<?php

return [

	'user_class' => 'App\Models\User',

	'workspace_class' => 'App\Models\Workspace',

	'excel_view' => 'innoboxrrdomainmanager::excel.',

	'notification_via' => ['mail', 'database'],

        'export_disk' => 's3',

        'search-options' => [
                'filtersPath' => 'vendor' . DIRECTORY_SEPARATOR . 'innoboxrr' . DIRECTORY_SEPARATOR . 'domain-manager' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Filters',
                'filtersNamespace' => 'Innoboxrr\\DomainManager\\Models\\Filters',
        ],

];