<?php

namespace App\Rules;

use App\Facades\GitHub;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GithubUsername implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Github::isUserOnGithub($value)) {
            $fail('The :attribute must be Github member.');
        }
    }
}
