<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Categories;

class ExistsOrZero implements Rule
{
    public function passes($attribute, $value)
    {
        return $value == 0 || Categories::where('id', $value)->exists();
    }

    public function message()
    {
        return 'The selected parent id is invalid or does not exist.';
    }

}
