<?php 

return [
    'api_url' => 'http://192.168.0.22:81/doadollar/api/v1/',
    'per_page' => 5,
    'site_timezone' => 'CDT', // By default CDT timezone wise date will display 
    'status' => [
        'active' => 1,
        'deactive' => 0,

    ],
    'entity_type' => [
        'superadmin'    => 0,
        'manager'       => 1,
        'user'          => 2,
        'guest'         => 3
    ]
];