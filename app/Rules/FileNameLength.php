<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileNameLength implements ValidationRule
{

    protected $maxLength;

    public function __construct($maxLength = 255)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // get the original file name
        $fileName = $value->getClientOriginalName();

        // check if the file name exceeds the maximum length
        if (strlen($fileName) > $this->maxLength) {
            $fail('The file name for '.$attribute.' must not exceed '.$this->maxLength.' characters.');
        }
    }
}
