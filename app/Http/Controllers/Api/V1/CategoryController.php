<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Data\Category\CreateCategoryData;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function store(CreateCategoryData $data): JsonResponse
    {
        $category = $this->categoryService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Categoria criada com sucesso',
            'data' => $category
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->categoryService->delete($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria não encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoria eliminada com sucesso'
        ], 204);
    }
}
