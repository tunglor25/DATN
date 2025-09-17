<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Super Admin Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho Super Admin - email của các tài khoản Super Admin
    | Chỉ những email này mới có quyền Super Admin
    |
    */
    'super_admin_emails' => [
        'minh662005@gmail.com',
        // Thêm email của bạn vào đây
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Permissions
    |--------------------------------------------------------------------------
    |
    | Cấu hình quyền hạn của các loại admin
    |
    */
    'permissions' => [
        'super_admin' => [
            'can_manage_all_users' => true,
            'can_manage_admins' => true,
            'can_create_admins' => true,
            'can_delete_admins' => true,
            'can_ban_admins' => true,
        ],
        'admin' => [
            'can_manage_all_users' => false,
            'can_manage_admins' => false,
            'can_create_admins' => false,
            'can_delete_admins' => false,
            'can_ban_admins' => false,
            'can_manage_regular_users' => true,
        ],
    ],
];
