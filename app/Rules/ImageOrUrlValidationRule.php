<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ImageOrUrlValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if (is_file($value)) {
            // Validate as an image file with allowed extensions and maximum size
            return Validator::make([$attribute => $value], [
                $attribute => 'image|mimes:jpeg,jpg,png|max:5'
            ])->passes();
        }

        // Check if the value is a valid URL
        return Validator::make([$attribute => $value], [$attribute => 'url'])->passes();
    }

    public function message()
    {
        return 'The :attribute must be a valid image file or a URL.';
    }
}
