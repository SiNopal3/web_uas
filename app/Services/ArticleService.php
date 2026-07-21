<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ArticleService
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Dapatkan daftar artikel analisis dengan pencarian dan paginasi/limit.
     */
    public function getArticlesList(array $filters = []): array
    {
        $query = Article::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $sortField = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        if (in_array($sortField, ['id', 'title', 'created_at'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 15;
        $paginator = $query->paginate($limit);

        return [
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'total' => $paginator->total(),
        ];
    }

    /**
     * Dapatkan detail satu artikel.
     */
    public function getArticleDetail(int $id): Article
    {
        return Article::findOrFail($id);
    }

    /**
     * Buat artikel baru.
     */
    public function createArticle(array $data, ?User $creator = null): Article
    {
        return DB::transaction(function () use ($data, $creator) {
            $article = Article::create([
                'title' => $data['title'],
                'author' => !empty($data['author']) ? $data['author'] : 'Admin RiskIntel',
                'url' => !empty($data['url']) ? $data['url'] : null,
                'content' => $data['content'],
            ]);

            $this->auditLogService->logAction($creator, 'CREATE_ARTICLE', 'Artikel Analisis', [
                'article_id' => $article->id,
                'title' => $article->title,
            ]);

            return $article;
        });
    }

    /**
     * Perbarui data artikel.
     */
    public function updateArticle(int $id, array $data, ?User $editor = null): Article
    {
        return DB::transaction(function () use ($id, $data, $editor) {
            $article = Article::findOrFail($id);

            $updateFields = [];
            if (isset($data['title'])) $updateFields['title'] = $data['title'];
            if (array_key_exists('author', $data)) $updateFields['author'] = !empty($data['author']) ? $data['author'] : 'Admin RiskIntel';
            if (array_key_exists('url', $data)) $updateFields['url'] = !empty($data['url']) ? $data['url'] : null;
            if (isset($data['content'])) $updateFields['content'] = $data['content'];

            $article->update($updateFields);

            $this->auditLogService->logAction($editor, 'UPDATE_ARTICLE', 'Artikel Analisis', [
                'article_id' => $article->id,
                'title' => $article->title,
                'fields' => array_keys($updateFields),
            ]);

            return $article;
        });
    }

    /**
     * Hapus artikel.
     */
    public function deleteArticle(int $id, ?User $deleter = null): bool
    {
        return DB::transaction(function () use ($id, $deleter) {
            $article = Article::findOrFail($id);
            $articleTitle = $article->title;

            $deleted = $article->delete();

            if ($deleted) {
                $this->auditLogService->logAction($deleter, 'DELETE_ARTICLE', 'Artikel Analisis', [
                    'deleted_article_id' => $id,
                    'deleted_article_title' => $articleTitle,
                ]);
            }

            return $deleted;
        });
    }
}
