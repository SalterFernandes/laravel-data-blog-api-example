<?php

namespace App\Services;

use App\Data\Category\CategoryData;
use App\Data\Category\CreateCategoryData;
use App\Repositories\CategoryRepository;
use Spatie\LaravelData\DataCollection;

readonly class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function getAll(): \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Enumerable|array|\Illuminate\Support\Collection|\Illuminate\Support\LazyCollection|\Spatie\LaravelData\PaginatedDataCollection|\Illuminate\Pagination\AbstractCursorPaginator|\Spatie\LaravelData\CursorPaginatedDataCollection|DataCollection|\Illuminate\Pagination\AbstractPaginator|\Illuminate\Contracts\Pagination\CursorPaginator
    {
        $categories = $this->categoryRepository->all();

        return CategoryData::collect($categories);
    }

    public function getById(int $id): ?CategoryData
    {
        $category = $this->categoryRepository->findById($id);

        return $category ? CategoryData::from($category)->include('posts', 'postsCount') : null;
    }

    public function create(CreateCategoryData $data): CategoryData
    {
        $category = $this->categoryRepository->create([
            'name' => $data->name,
        ]);

        return CategoryData::from($category);
    }

    public function delete(int $id): bool
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return false;
        }

        return $this->categoryRepository->delete($category);
    }
}
