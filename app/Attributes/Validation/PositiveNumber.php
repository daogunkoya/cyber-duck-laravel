<?php


namespace App\Attributes\Validation;

use Attribute;
use Illuminate\Contracts\Validation\Rule;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class PositiveNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        return is_numeric($value) && $value > 0;
    }

    public function message(): string
    {
        return 'The :attribute must be a positive number';
    }
}