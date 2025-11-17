<?php

namespace App\Data\Category;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;

class CreateCategoryData extends Data
{
    public function __construct(
        #[Required, Min(3), Unique('categories', 'name')]
        public string $name,
    ) {}

    public static function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres',
            'name.unique' => 'Esta categoria já existe',
        ];
    }
}
