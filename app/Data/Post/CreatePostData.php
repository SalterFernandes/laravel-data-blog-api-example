<?php

namespace App\Data\Post;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\ArrayType;

class CreatePostData extends Data
{
    public function __construct(
        #[Required, Min(3)]
        public string $title,

        #[Required, Min(10)]
        public string $content,

        #[Required]
        public string $excerpt,

        #[ArrayType, Exists('categories', 'id')]
        public array $categoryIds = [],
    ) {}

    public static function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório',
            'title.min' => 'O título deve ter no mínimo 3 caracteres',
            'content.required' => 'O conteúdo é obrigatório',
            'content.min' => 'O conteúdo deve ter no mínimo 10 caracteres',
            'excerpt.required' => 'O resumo é obrigatório',
            'categoryIds.*.exists' => 'Categoria inválida',
        ];
    }
}
