<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\UserManagementService;
use App\Services\AdminService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserManagementService $userManagementService;
    protected AdminService $adminService;

    public function __construct(UserManagementService $userManagementService, AdminService $adminService)
    {
        $this->userManagementService = $userManagementService;
        $this->adminService = $adminService;
    }

    /**
     * Tampilkan halaman admin (`/admin/users`).
     */
    public function index(Request $request): View
    {
        $kpi = $this->adminService->getDashboardKpiCards();
        $summary = $this->adminService->generateAutomaticDashboardSummary();
        $settings = $this->adminService->getSystemSettings();

        return view('admin.index', compact('kpi', 'summary', 'settings'));
    }

    /**
     * Simpan pengguna baru (`POST /admin/users`).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $user = $this->userManagementService->createUser($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Pengguna baru berhasil dibuat.',
            'data' => $user->load('roles', 'role'),
        ]);
    }

    /**
     * Perbarui pengguna dan perannya (`PUT /admin/users/{id}`).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'username' => 'nullable|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $user = $this->userManagementService->updateUser($id, $validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui.',
            'data' => $user->load('roles', 'role'),
        ]);
    }

    /**
     * Hapus pengguna (`DELETE /admin/users/{id}`).
     */
    public function destroy(int $id): JsonResponse
    {
        if ($id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri saat sedang aktif masuk.',
            ], 422);
        }

        $deleted = $this->userManagementService->deleteUser($id, Auth::user());

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Akun pengguna berhasil dihapus.' : 'Gagal menghapus pengguna.',
        ]);
    }

    /**
     * Endpoint AJAX untuk daftar pengguna (`GET /api/admin/users-list`).
     */
    public function getUsersData(Request $request): JsonResponse
    {
        $usersData = $this->userManagementService->getUsersList($request->all());
        $kpi = $this->userManagementService->getUserSummaryKpi();
        return response()->json([
            'success' => true,
            'data' => $usersData,
            'kpi' => $kpi,
        ]);
    }

    /**
     * Tampilkan detail satu pengguna (`GET /api/admin/users/{id}`).
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userManagementService->getUserDetail($id);
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Perbarui status pengguna via dropdown (`PATCH /admin/users/{id}/status`).
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:active,inactive,suspended',
        ]);

        $user = $this->userManagementService->updateUserStatus($id, $validated['status'], Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Status pengguna berhasil diperbarui menjadi ' . strtoupper($validated['status']),
            'data' => $user->load('roles', 'role'),
        ]);
    }
}
