<?php 

return [
    'api_url' => 'http://192.168.0.22:81/doadollar/api/v1/',
    'per_page' => 5,
    'cache_expiry' => 86400, // in seconds (60*60*24 = 1 day) 
    'site_timezone' => 'CDT', // By default CDT timezone wise date will display 
    'storage_path' => '/app/public/',
    'upload_path' => 'public/',
    'link_path' => 'storage/app/public/',
    'status' => [
        'active' => 1,
        'deactive' => 0,

    ],
    'entity_type' => [
        'superadmin'    => 0,
        'manager'       => 1,
        'user'          => 2,
        'guest'         => 3
    ],
    'campaign_status' => [
        'pending' => 0,
        'ongoing' => 1,
        'completed' => 2,
        'cancelled' => 3,
        'rejected' => 4,
        'approved' => 5,
    ],
];