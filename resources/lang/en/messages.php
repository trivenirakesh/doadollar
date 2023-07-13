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
        'type' => 'Please enter type',
        'type_numeric' => 'Type value must be numeric',
        'type_lte' => 'Type should be 0 or 1',
        'old_password' => 'Please enter old password',
        'new_password' => 'Please enter new password',
        'id' => 'Please enter id',
        'id_numeric' => 'Id must be numeric',
        'email_password_wrong' => 'Email or password you entered did not match our records.',
        'user_not_found' => 'User not found'

    ],

    'success' => [
        'details' => ' details fetch successfully',
        'create' => ' created successfully',
        'update' => ' created successfully',
        'delete' => ' created successfully',
        'password_reset' => 'Password reset successfully',
        'old_password_wrong' => "Old Password Doesn't match",
        'user_logout' => 'User logout successfully',
        'user_login' => 'User successfully logged in'
    ]
];