<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User; // Import model User

class TeacherHasRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Cek apakah user ID ada
        $user = User::find($value);

        if (!$user) {
            $fail('Guru yang dipilih tidak ditemukan.');
            return;
        }

        // 2. Cek apakah user memiliki peran 'guru'
        if (!$user->hasRole('guru')) {
            $fail('Pengguna yang dipilih bukan seorang guru.');
        }
    }
}
