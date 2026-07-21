<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Dapatkan daftar artikel AJAX (`GET /api/admin/articles-list`).
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->articleService->getArticlesList($request->all());
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Dapatkan detail artikel (`GET /api/admin/articles/{id}`).
     */
    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->getArticleDetail($id);
        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }

    /**
     * Simpan artikel baru (`POST /admin/articles`).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:2048',
            'content' => 'required|string',
        ]);

        $article = $this->articleService->createArticle($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Artikel analisis berhasil ditambahkan.',
            'data' => $article,
        ]);
    }

    /**
     * Perbarui data artikel (`PUT /admin/articles/{id}`).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:2048',
            'content' => 'required|string',
        ]);

        $article = $this->articleService->updateArticle($id, $validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Artikel analisis berhasil diperbarui.',
            'data' => $article,
        ]);
    }

    /**
     * Hapus artikel (`DELETE /admin/articles/{id}`).
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->articleService->deleteArticle($id, Auth::user());

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Artikel analisis berhasil dihapus.' : 'Gagal menghapus artikel.',
        ]);
    }
}
