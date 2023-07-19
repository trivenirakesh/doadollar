<?php 

return [
    'per_page' => 5,
    'cache_expiry' => 86400, // in seconds (60*60*24 = 1 day) 
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