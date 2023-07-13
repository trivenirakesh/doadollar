<?php 

return [
    'validation' => [
        'not_found' => ' not found',
        'first_name' => 'Please enter first name',
        'last_name' => 'Please enter last name',
        'name' => 'Please enter name',
        'email' => 'Please enter email',
        'email_email' => 'Invalid email address',
        'email_unique' => 'Email address is already registered. Please, use a different email',
        'mobile' => 'Please enter mobile',
        'mobile_numeric' => 'Mobile must be numeric',
        'mobile_digits' => 'Mobile should be 10 digit number',
        'mobile_unique' => 'Mobile number is already registered. Please, use a different mobile',
        'password' => 'Please enter password',
        'strong_password' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.',
        'role_id' => 'Please enter role',
        'role_id_numeric' => 'Role value must be numeric',
        'status' => 'Please enter status',
        'status_numeric' => 'Status value must be numeric',
        'status_lte' => 'Status should be 0 or 1',
        'image' => 'Please select image',
        'image-max' => 'Please select below 2 MB images',
        'image-mimes' => 'Please select only jpg, png, jpeg files',
        'api_key' => 'Please enter api key',
        'secret_key' => 'Please enter secret key',
        'entity_type' => 'Please enter entity type',
        'entity_type_digits' => 'Entity type value must be numeric',
        'entity_type_lte' => 'Entity type value must between 0 and 2',

    ],

    'success' => [
        'details' => ' details fetch successfully',
        'create' => ' created successfully',
        'update' => ' created successfully',
        'delete' => ' created successfully',
    ]
];