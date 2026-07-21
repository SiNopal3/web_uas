<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\PortService;
use Illuminate\Support\Facades\Auth;

class PortController extends Controller
{
    protected PortService $portService;

    public function __construct(PortService $portService)
    {
        $this->portService = $portService;
    }

    /**
     * Dapatkan daftar pelabuhan AJAX (`GET /api/admin/ports-list`).
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->portService->getPortsList($request->all());
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Dapatkan detail pelabuhan (`GET /api/admin/ports/{id}`).
     */
    public function show(int $id): JsonResponse
    {
        $port = $this->portService->getPortDetail($id);
        return response()->json([
            'success' => true,
            'data' => $port,
        ]);
    }

    /**
     * Simpan pelabuhan baru (`POST /admin/ports`).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $port = $this->portService->createPort($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Data pelabuhan berhasil ditambahkan.',
            'data' => $port,
        ]);
    }

    /**
     * Perbarui data pelabuhan (`PUT /admin/ports/{id}`).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $port = $this->portService->updatePort($id, $validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Data pelabuhan berhasil diperbarui.',
            'data' => $port,
        ]);
    }

    /**
     * Hapus pelabuhan (`DELETE /admin/ports/{id}`).
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->portService->deletePort($id, Auth::user());

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Data pelabuhan berhasil dihapus.' : 'Gagal menghapus pelabuhan.',
        ]);
    }
}
