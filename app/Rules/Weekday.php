<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Weekday implements Rule
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
        // Vérifier si la date est un jour de la semaine (lundi à vendredi)
        return date('N', strtotime($value)) <= 5;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La date doit être un jour de la semaine (lundi à vendredi).';
    }
}
