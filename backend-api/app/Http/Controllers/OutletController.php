<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * List all outlets.
     */
    public function index()
    {
        $outlets = \App\Models\Outlet::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $outlets
        ]);
    }

    public function validateCode(Request $request)
    {
        $request->validate([
            'kode_outlet' => 'required|string',
        ]);

        $outlet = \App\Models\Outlet::where('kode_outlet', $request->kode_outlet)->first();

        if (!$outlet) {
            return response()->json([
                'success' => false,
                'message' => 'Kode meja tidak valid atau tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode meja valid.',
            'data' => [
                'id' => $outlet->id,
                'name' => $outlet->name,
                'kode_outlet' => $outlet->kode_outlet,
                'nomor_meja' => $outlet->nomor_meja ?? '01',
            ]
        ], 200);
    }
}
