<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    /**
     * Ambil daftar pantauan favorit pengguna.
     */
    public function index(Request $request)
    {
        $favorites = Watchlist::with('country')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(function ($w) {
                return [
                    'id' => $w->id,
                    'country_id' => $w->country_id,
                    'country_name' => $w->country?->name ?? 'Unknown',
                    'currency' => $w->country?->currency ?? 'USD',
                    'region' => $w->country?->region ?? 'Global',
                    'created_at' => $w->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    /**
     * Simpan negara baru ke daftar pantauan pengguna.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_name' => 'required|string',
            'currency' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        $user = $request->user();
        $countryName = trim($request->input('country_name'));

        // Cari atau buat negara di tabel countries
        $country = Country::firstOrCreate(
            ['name' => $countryName],
            [
                'currency' => $request->input('currency', 'USD'),
                'region' => $request->input('region', 'Global'),
            ]
        );

        // Periksa apakah sudah ada di watchlist user
        $watchlist = Watchlist::firstOrCreate([
            'user_id' => $user->id,
            'country_id' => $country->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Negara berhasil ditambahkan ke daftar pantauan.',
            'data' => [
                'id' => $watchlist->id,
                'country_id' => $country->id,
                'country_name' => $country->name,
                'currency' => $country->currency,
            ]
        ]);
    }

    /**
     * Hapus negara dari daftar pantauan pengguna.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        // Hapus berdasarkan ID watchlist, atau berdasarkan country_id / country_name
        if (is_numeric($id)) {
            $deleted = Watchlist::where('user_id', $user->id)
                ->where(function ($q) use ($id) {
                    $q->where('id', $id)->orWhere('country_id', $id);
                })
                ->delete();
        } else {
            $country = Country::where('name', $id)->first();
            $deleted = 0;
            if ($country) {
                $deleted = Watchlist::where('user_id', $user->id)
                    ->where('country_id', $country->id)
                    ->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => $deleted ? 'Berhasil dihapus dari pantauan.' : 'Data tidak ditemukan.',
        ]);
    }
}
