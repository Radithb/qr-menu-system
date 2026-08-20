<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Get all menus for customer catalog (regardless of table code).
     */
    public function getMenuByOutlet($outlet_id = null)
    {
        $menus = Menu::orderBy('category')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $menus
        ], 200);
    }

    /**
     * List all menus for cashier management.
     */
    public function index(Request $request)
    {
        $menus = Menu::orderBy('category')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }

    /**
     * Create a new menu item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:500',
            'variants' => 'nullable|array',
        ]);

        $menu = Menu::create([
            'outlet_id' => $request->outlet_id ?? 1,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'variants' => $request->variants,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan',
            'data' => $menu
        ], 201);
    }

    /**
     * Update an existing menu item.
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:500',
            'variants' => 'nullable|array',
        ]);

        $menu->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $request->image_url,
            'variants' => $request->variants,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui',
            'data' => $menu
        ]);
    }

    /**
     * Delete a menu item.
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus'
        ]);
    }
}
