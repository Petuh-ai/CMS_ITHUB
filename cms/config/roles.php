<?php
/**
 * Конфигурация ролей и прав доступа
 */

return [
    'roles' => [
        'admin' => [
            'name' => 'Администратор',
            'permissions' => [
                'view_dashboard',
                'manage_posts',
                'manage_categories',
                'manage_media',
                'manage_users',
                'manage_settings',
                'view_reports',
                'manage_roles',
            ],
        ],
        'editor' => [
            'name' => 'Редактор',
            'permissions' => [
                'view_dashboard',
                'manage_posts',
                'manage_categories',
                'manage_media',
                'publish_posts',
            ],
        ],
        'author' => [
            'name' => 'Автор',
            'permissions' => [
                'view_dashboard',
                'create_draft_posts',
                'edit_own_posts',
                'upload_media',
            ],
        ],
    ],
];
