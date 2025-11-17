<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Data\Post\CreatePostData;
use App\Data\Post\UpdatePostData;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $posts = $this->postService->getAll($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show(string $idOrSlug): JsonResponse
    {
        // Tenta encontrar por ID primeiro, depois por slug
        $post = is_numeric($idOrSlug)
            ? $this->postService->getById((int) $idOrSlug)
            : $this->postService->getBySlug($idOrSlug);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    public function store(CreatePostData $data, Request $request): JsonResponse
    {
        $post = $this->postService->create($data, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Post criado com sucesso',
            'data' => $post
        ], 201);
    }

    public function update(UpdatePostData $data, int $id, Request $request): JsonResponse
    {
        $post = $this->postService->getById($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post não encontrado'
            ], 404);
        }

        // Verificar se o user é o autor
        if ($post->author->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado'
            ], 403);
        }

        $updatedPost = $this->postService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Post atualizado com sucesso',
            'data' => $updatedPost
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        $post = $this->postService->getById($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post não encontrado'
            ], 404);
        }

        // Verificar se o user é o autor
        if ($post->author->id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado'
            ], 403);
        }

        $this->postService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Post eliminado com sucesso'
        ], 204);
    }

    public function byUser(int $userId, Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $posts = $this->postService->getByUser($userId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function byCategory(int $categoryId, Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $posts = $this->postService->getByCategory($categoryId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
}
