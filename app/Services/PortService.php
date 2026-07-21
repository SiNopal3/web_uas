<?php

namespace App\Services;

use App\Models\Port;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PortService
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Dapatkan daftar pelabuhan dengan pencarian dan paginasi/limit.
     */
    public function getPortsList(array $filters = []): array
    {
        $query = Port::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['country']) && $filters['country'] !== 'all') {
            $query->where('country', $filters['country']);
        }

        $sortField = $filters['sort_by'] ?? 'id';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        if (in_array($sortField, ['id', 'name', 'country', 'created_at'])) {
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
     * Dapatkan detail satu pelabuhan.
     */
    public function getPortDetail(int $id): Port
    {
        return Port::findOrFail($id);
    }

    /**
     * Buat pelabuhan baru.
     */
    public function createPort(array $data, ?User $creator = null): Port
    {
        return DB::transaction(function () use ($data, $creator) {
            $port = Port::create([
                'name' => $data['name'],
                'location' => $data['location'],
                'country' => $data['country'],
            ]);

            $this->auditLogService->logAction($creator, 'CREATE_PORT', 'Dataset Pelabuhan', [
                'port_id' => $port->id,
                'port_name' => $port->name,
                'country' => $port->country,
            ]);

            return $port;
        });
    }

    /**
     * Perbarui data pelabuhan.
     */
    public function updatePort(int $id, array $data, ?User $editor = null): Port
    {
        return DB::transaction(function () use ($id, $data, $editor) {
            $port = Port::findOrFail($id);

            $updateFields = [];
            if (isset($data['name'])) $updateFields['name'] = $data['name'];
            if (isset($data['location'])) $updateFields['location'] = $data['location'];
            if (isset($data['country'])) $updateFields['country'] = $data['country'];

            $port->update($updateFields);

            $this->auditLogService->logAction($editor, 'UPDATE_PORT', 'Dataset Pelabuhan', [
                'port_id' => $port->id,
                'port_name' => $port->name,
                'fields' => array_keys($updateFields),
            ]);

            return $port;
        });
    }

    /**
     * Hapus pelabuhan.
     */
    public function deletePort(int $id, ?User $deleter = null): bool
    {
        return DB::transaction(function () use ($id, $deleter) {
            $port = Port::findOrFail($id);
            $portName = $port->name;

            $deleted = $port->delete();

            if ($deleted) {
                $this->auditLogService->logAction($deleter, 'DELETE_PORT', 'Dataset Pelabuhan', [
                    'deleted_port_id' => $id,
                    'deleted_port_name' => $portName,
                ]);
            }

            return $deleted;
        });
    }
}
