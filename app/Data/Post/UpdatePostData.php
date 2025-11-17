<?php

namespace App\Data\Post;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Optional;

class UpdatePostData extends Data
{
    public function __construct(
        #[Min(3)]
        public string|Optional $title,

        #[Min(10)]
        public string|Optional $content,

        public string|Optional $excerpt,

        #[ArrayType, Exists('categories', 'id')]
        public array|Optional $categoryIds,
    ) {}

}
