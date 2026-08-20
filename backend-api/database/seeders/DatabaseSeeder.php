<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $outletsData = [
            ['name' => 'Kopi Ruang Hati', 'kode_outlet' => 'S2LZ6B', 'nomor_meja' => '01'],
            ['name' => 'Kopi Ruang Hati', 'kode_outlet' => 'MEJA01', 'nomor_meja' => '01'],
            ['name' => 'Kopi Ruang Hati', 'kode_outlet' => 'MEJA02', 'nomor_meja' => '02'],
            ['name' => 'Kopi Ruang Hati', 'kode_outlet' => 'MEJA03', 'nomor_meja' => '03'],
        ];

        $createdOutlets = [];
        foreach ($outletsData as $od) {
            $createdOutlets[] = Outlet::create($od);
        }

        $addOnsMinuman = [
            [
                'name' => 'Add On',
                'type' => 'multiple',
                'options' => [
                    ['name' => 'Oat Milk', 'price' => 8000],
                    ['name' => 'Espresso', 'price' => 5000],
                    ['name' => 'Lychee', 'price' => 5000],
                    ['name' => 'Macchiato', 'price' => 3000],
                    ['name' => 'Jelly', 'price' => 2000]
                ]
            ]
        ];

        $menus = [
            // === MAIN COURSE ===
            ['name' => 'Nasi Chicken Katsu', 'category' => 'Main Course', 'price' => 35000, 'variants' => null],
            ['name' => 'Nasi Lunos Sambel Korek', 'description' => 'Telur, Nugget, Sosis', 'category' => 'Main Course', 'price' => 30000, 'variants' => null],
            ['name' => 'Spaghetti Bolognese', 'category' => 'Main Course', 'price' => 25000, 'variants' => null],
            ['name' => 'Indomie Telur', 'category' => 'Main Course', 'price' => 15000, 'variants' => [
                ['name' => 'Penyajian', 'type' => 'single', 'options' => [['name' => 'Goreng', 'price' => 0], ['name' => 'Kuah', 'price' => 0]]]
            ]],
            ['name' => 'Sate Taichan', 'category' => 'Main Course', 'price' => 30000, 'variants' => [
                ['name' => 'Add Nasi', 'type' => 'single', 'options' => [['name' => 'Tidak', 'price' => 0], ['name' => 'Ya (+5k)', 'price' => 5000]]]
            ]],

            // === LIGHT MEALS & PLATTERS ===
            ['name' => 'Mix Platter', 'description' => 'Kentang, Sosis, Nugget', 'category' => 'Light Meals & Platters', 'price' => 25000, 'variants' => null],
            ['name' => 'Tahu Bakso', 'category' => 'Light Meals & Platters', 'price' => 25000, 'variants' => null],
            ['name' => 'Cireng Isi Ayam', 'category' => 'Light Meals & Platters', 'price' => 30000, 'variants' => null],
            ['name' => 'Cireng Original', 'category' => 'Light Meals & Platters', 'price' => 25000, 'variants' => null],
            ['name' => 'Kentang Goreng', 'category' => 'Light Meals & Platters', 'price' => 22000, 'variants' => null],
            ['name' => 'Singkong Goreng', 'category' => 'Light Meals & Platters', 'price' => 22000, 'variants' => null],
            ['name' => 'Tempe Goreng', 'category' => 'Light Meals & Platters', 'price' => 18000, 'variants' => null],

            // === WARM & SPICY BOWLS ===
            ['name' => 'Baso Aci', 'category' => 'Warm & Spicy Bowls', 'price' => 27000, 'variants' => [
                ['name' => 'Add Telur', 'type' => 'single', 'options' => [['name' => 'Tidak', 'price' => 0], ['name' => 'Ya (+5k)', 'price' => 5000]]]
            ]],
            ['name' => 'Seblak', 'category' => 'Warm & Spicy Bowls', 'price' => 27000, 'variants' => [
                ['name' => 'Add Telur', 'type' => 'single', 'options' => [['name' => 'Tidak', 'price' => 0], ['name' => 'Ya (+5k)', 'price' => 5000]]]
            ]],
            ['name' => 'Pempek Adaan', 'category' => 'Warm & Spicy Bowls', 'price' => 25000, 'variants' => null],

            // === SWEET BITES ===
            ['name' => 'Churros', 'category' => 'Sweet Bites', 'price' => 25000, 'variants' => null],
            ['name' => 'Pisang Goreng', 'category' => 'Sweet Bites', 'price' => 20000, 'variants' => [
                ['name' => 'Rasa', 'type' => 'single', 'options' => [['name' => 'Original', 'price' => 0], ['name' => 'Cokelat', 'price' => 0], ['name' => 'Keju', 'price' => 0]]]
            ]],

            // === COFFEE ===
            ['name' => 'Caramel Machiato', 'category' => 'Coffee', 'price' => 25000, 'variants' => $addOnsMinuman],
            ['name' => 'Kopi Susu Aren', 'description' => 'Milk + aren + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Kopi Susu Latte', 'description' => 'Milk + sympe syrup + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Kopi Susu Caramel', 'description' => 'Milk + caramel + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'RH Signature', 'description' => 'Secret menu', 'category' => 'Coffee', 'price' => 30000, 'variants' => $addOnsMinuman],
            ['name' => 'Java Americano', 'description' => 'Aren + espresso', 'category' => 'Coffee', 'price' => 20000, 'variants' => $addOnsMinuman],
            ['name' => 'Rhum Latte', 'description' => 'Milk + rhum + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Rhum Regal Latte', 'description' => 'Milk + rhum + espresso + regal', 'category' => 'Coffee', 'price' => 25000, 'variants' => $addOnsMinuman],
            ['name' => 'Banana Coffee', 'description' => 'Milk + banana + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Honey Latte', 'description' => 'Milk + honey + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Matcha Madness', 'description' => 'Milk + matcha + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Milo Latte', 'description' => 'Milk + milo + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Hazelnut Latte', 'description' => 'Milk + hazelnut + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Pandan Latte', 'description' => 'Milk + pandan syrup + espresso', 'category' => 'Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Butterscotch', 'category' => 'Coffee', 'price' => 28000, 'variants' => $addOnsMinuman],

            // === NON COFFEE ===
            ['name' => 'Black Sugar Milo Dino', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Milo Macchiato', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Taro Latte', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Matcha Latte', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Chocolatte', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Choco Nutella', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Choco Banana', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Mango Jelly', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Mango Macchiato', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Mango Yakult', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Lychee Tea', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Lychee Yakult', 'category' => 'Non Coffee', 'price' => 25000, 'variants' => $addOnsMinuman],
            ['name' => 'Dreamy Berry Blueberry', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Dreamy Berry Strawberry', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Black Sugar Freshmilk', 'category' => 'Non Coffee', 'price' => 20000, 'variants' => $addOnsMinuman],
            ['name' => 'Ovaltine Macchiato', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Red Oreo Velvet', 'category' => 'Non Coffee', 'price' => 23000, 'variants' => $addOnsMinuman],
            ['name' => 'Milktea', 'category' => 'Non Coffee', 'price' => 20000, 'variants' => $addOnsMinuman],
            ['name' => 'Lemon Tea', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Honey Lime', 'category' => 'Non Coffee', 'price' => 22000, 'variants' => $addOnsMinuman],
            ['name' => 'Ice Tea', 'category' => 'Non Coffee', 'price' => 15000, 'variants' => $addOnsMinuman],

            // === HOT DRINK ===
            ['name' => 'Hot Kopi Susu Aren', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Java Americano', 'category' => 'Hot Drink', 'price' => 20000, 'variants' => null],
            ['name' => 'Hot Cappucino', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Honey Lime', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Taro', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Matcha', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Chocolatte', 'category' => 'Hot Drink', 'price' => 23000, 'variants' => null],
            ['name' => 'Hot Tea', 'category' => 'Hot Drink', 'price' => 15000, 'variants' => null],
        ];

        $firstOutlet = $createdOutlets[0];
        foreach ($menus as $m) {
            $m['outlet_id'] = $firstOutlet->id;
            Menu::create($m);
        }
    }
}
