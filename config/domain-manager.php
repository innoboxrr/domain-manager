<?php

return [

	'user_class' => 'App\Models\User',

	'workspace_class' => 'App\Models\Workspace',

	'excel_view' => 'innoboxrrdomainmanager::excel.',

	'notification_via' => ['mail', 'database'],

	'export_disk' => 's3',
	
];