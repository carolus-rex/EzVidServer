<?php
return [
    'namespaces' => [
    	'Components' => base_path().DIRECTORY_SEPARATOR.'components',
    	'App' => base_path().DIRECTORY_SEPARATOR.'app'
    ],

    'protection_middleware' => [
    	'auth:api'
    ],

    'resource_namespace' => 'resources',

    'language_folder_name' => 'lang',

    'view_folder_name' => 'views'
];
