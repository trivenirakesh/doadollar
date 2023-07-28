<?php

return [
    'validation' => [
        'not_found' => ' not found',
        'first_name' => 'Please enter first name',
        'last_name' => 'Please enter last name',
        'name' => 'Please enter name',
        'max_name' => 'Name can not be more than 255 characters',
        'email' => 'Please enter email',
        'email_email' => 'Invalid email address',
        'email_unique' => 'Email address is already registered. Please, use a different email',
        'mobile' => 'Please enter mobile',
        'mobile_digits' => 'Mobile should be 10 digit number',
        'mobile_unique' => 'Mobile number is already registered. Please, use a different mobile',
        'password' => 'Please enter password',
        'strong_password' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.',
        'role_id' => 'Please enter role',
        'status' => 'Please enter status',
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
        'type_lte' => 'Type should be 0 or 1',
        'old_password' => 'Please enter old password',
        'new_password' => 'Please enter new password',
        'id' => 'Please enter id',
        'email_password_wrong' => 'Email or password you entered did not match our records.',
        'user_not_found' => 'User not found',
        'unique_code' => 'Please enter unique code',
        'unique_code_unique' => 'This unique code already used',
        'campaign_category_id' => 'Please enter campaign category id',
        'start_datetime' => 'Please enter campaign start datetime',
        'end_datetime' => 'Please enter campaign end datetime',
        'donation_target' => 'Please enter donation target',
        'must_numeric' => ' must be numeric',
        'title' => 'Please enter title',
        'subject' => 'Please enter subject',
        'message' => 'Please enter message',
        'alpha_num' => ' only contain letters and numbers',
        'max' => 'Max 200 characters allow',
        'password_confirmed' => 'The password confirmation does not match.',
        'password_min' => "Invalid password",
        'title_invalid' => 'Title is invalid',
        'password_confirmation' => 'Please enter password confirmation',
        'password_confirmation_same' => 'The password confirmation does not match',
        'old_password_incorrect' => 'The old password is incorrect.',
        'new_password_min' => 'Password must 8 character.'
    ],
    'campaigns' => [
        'files_uplaod_array' => 'The files field must be an array.',
        'files_uplaod_title_required' => 'Please enter a title for each file.',
        'files_uplaod_title_max' => 'The title must not exceed 255 characters.',
        'files_uplaod_description_max' => 'The description must not exceed 2500 characters.',
        'files_uplaod_file_required' => 'Please upload a file.',
        'files_uplaod_file_image' => 'The uploaded image is invalid.',
        'files_uplaod_file_mimes' => 'Only JPEG, PNG, and JPG files are allowed.',
        'files_uplaod_file_max' => 'The file size should not exceed 2 MB.',
        'video.array' => 'The video url field must be an array.',
        'video_title_required' => 'Please enter a video title for each video url.',
        'video_title_max' => 'The video title must not exceed 255 characters.',
        'video_description_max' => 'The video description must not exceed 2500 characters.',
        'video_link_required' => 'Please enter video link.',
        'video_link_regex' => 'The video link is invalid.',
        'video_link_max' => 'The video link must not exceed 255 characters.',

        'start_datetime_required' => 'The start date is required.',
        'start_datetime_date_format' => 'The start date must be in the format Y-m-d H:i:s.',
        'start_datetime_before_or_equal' => 'The start date must be before or equal to the end date.',
        'end_datetime_required' => 'The end date is required.',
        'end_datetime_date_format' => 'The end date must be in the format Y-m-d H:i:s.',
        'end_datetime_after_or_equal' => 'The end date must be after or equal to the start date.',
    ],

    'donation' => [
        'campaign_id' => 'Please select campaign id',
        'payment_type_id' => 'Please select payment type',
        'entity_id' => 'Please enter user id',
        'donation_amount' => 'Please enter donation amount'
    ],

    'auth' => [
        'login_failed' => 'These credentials do not match our records.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'password_reset_link_success' => 'Reset password mail send to your registered mail.',
        'password_reset_link_failed' => 'Password reset link failed, Something went wrong!!',
        'token' => 'Token field is required',
        'password_reset_success' => 'Your password has been changed successfully. Please log in to continue.',
        'password_reset_failed' => 'Password reset failed, Something went wrong!!',
    ],

    'success' => [
        'details' => ' details fetch successfully',
        'create' => ' created successfully',
        'update' => ' updated successfully',
        'delete' => ' deleted successfully',
        'password_reset' => 'Password reset successfully',
        'old_password_wrong' => "Old Password Doesn't match",
        'user_logout' => 'User logout successfully',
        'user_login' => 'User successfully logged in',
        'list' => ' list fetch successfully',
    ],
    'failed' => [
        'general' => 'Something went wrong!!',
    ]
];
