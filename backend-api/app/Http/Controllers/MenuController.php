<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenuByOutlet($outlet_id)
    {
        $menus = \App\Models\Menu::where('outlet_id', $outlet_id)->get();

        return response()->json([
            'success' => true,
            'data' => $menus
        ], 200);
    }
}
