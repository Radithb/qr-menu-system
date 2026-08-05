<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function validateCode(Request $request)
    {
        $request->validate([
            'kode_outlet' => 'required|string',
        ]);

        $outlet = \App\Models\Outlet::where('kode_outlet', $request->kode_outlet)->first();

        if (!$outlet) {
            return response()->json([
                'success' => false,
                'message' => 'Kode outlet tidak valid atau tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode outlet valid.',
            'data' => [
                'id' => $outlet->id,
                'name' => $outlet->name,
                'kode_outlet' => $outlet->kode_outlet,
                'nomor_meja' => $outlet->nomor_meja ?? '01',
            ]
        ], 200);
    }
}
