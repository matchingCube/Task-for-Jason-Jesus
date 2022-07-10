<?php

return  [
    'roles' => [
        '0' => 'Removed User',
        '1' => 'Super Admin',
        '2' => 'Admin',
        '3' => 'Owner',
        '4' => 'HR',
        '5' => 'Operations',
        '6' => 'Dispatcher',
        '7' => 'Driver',
        '8' => 'Accountant',
    ],
    'permissions' => [
        'manage-user'               => ['1'],
        'manage-global-setting'     => ['1', '2', '3'],
        'manage-driver'             => ['1', '2', '3', '4', '8'],
        'manage-fleet'              => ['1', '2', '3', '5'],
        'manage-schedule'           => ['1', '2', '3', '5'],
        'manage-gf-statement'       => ['1', '2', '3'],
        'manage-dashboard'          => ['1', '2', '3'],
        'manage-payroll'            => ['1', '2', '3', '8'],
        'manage-payroll-setting'    => ['1', '2', '3'],
    ]
];