<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value is a valid base64 string
        if (!preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $value)) {
            return false;
        }

        // Decode the base64 string and check if it's a valid image
        $image = base64_decode(preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $value));
        $image_info = getimagesizefromstring($image);
        if (empty($image_info)) {
            return false;
        }

        // Make sure the file extension matches the image type
        $extensions = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
        // if (!in_array($image_info['mime'], $extensions) || !preg_match('/^data:image\/' . $image_info['mime'] . ';base64,/', $value)) {
            if (!in_array($image_info['mime'], $extensions)) {
            return false;
        }

        // Get the size of the decoded image
        $image_size = strlen($image);
        // print_r($image_size);exit;
        if ($image_size > $this->maxSize()) {
            // The image is too big
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The validation error message.';
        return __('The :attribute must must not more than 10 mb.');
    }

    protected function maxSize()
    {
        // Set your maximum image size in bytes
        // return 5000000; // 5MB
        return 83889000; //10 MB
        // return 85000; // 2MB
    }
}
