<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Breakfast Menu
        $breakfast = Category::create([
            'name' => 'Breakfast Menu',
            'slug' => 'breakfast',
            'description' => 'Morning meals and breakfast items',
            'order' => 1,
        ]);

        $breakfastItems = [
            ['name' => 'Breakfast Chicken Inasal Regular', 'base_price' => 156],
            ['name' => 'Breakfast Pork Sisig', 'base_price' => 156],
            ['name' => 'Breakfast Pork BBQ', 'base_price' => 156],
        ];

        foreach ($breakfastItems as $item) {
            $menuItem = MenuItem::create([
                'category_id' => $breakfast->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Solo',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'With Drink',
                'price' => $item['base_price'] + 28,
                'order' => 2,
            ]);
        }

        // Chicken Menu
        $chicken = Category::create([
            'name' => 'Chicken Menu',
            'slug' => 'chicken',
            'description' => 'Spicy and regular chicken inasal variants',
            'order' => 2,
        ]);

        $chickenItems = [
            ['name' => 'Spicy Paa Large - PM1', 'base_price' => 160],
            ['name' => 'Spicy Pecho Large - PM2', 'base_price' => 188],
            ['name' => 'Spicy Paa Large Family Size', 'base_price' => 593],
            ['name' => 'Spicy Pecho Large Family Size', 'base_price' => 705],
            ['name' => 'Spicy Paa & Pecho Family Size', 'base_price' => 641],
            ['name' => 'Spicy Chicken Inasal Trio', 'base_price' => 423],
            ['name' => 'Spicy Chicken Inasal & Pork BBQ Buddy Size', 'base_price' => 385],
            ['name' => 'Spicy Paa Large Buddy Size', 'base_price' => 301],
            ['name' => 'Spicy Pecho Large Buddy Size', 'base_price' => 357],
            ['name' => 'Spicy Paa & Pecho Buddy Size', 'base_price' => 337],
            ['name' => 'Paa & Pecho Buddy Size', 'base_price' => 315],
            ['name' => 'Paa Large - PM1', 'base_price' => 149],
            ['name' => 'Pecho Large - PM2', 'base_price' => 177],
            ['name' => 'Paa Large Family Size', 'base_price' => 549],
            ['name' => 'Pecho Large Family Size', 'base_price' => 661],
            ['name' => 'Paa & Pecho Family Size', 'base_price' => 596],
            ['name' => 'Chicken Inasal Trio', 'base_price' => 399],
            ['name' => 'Chicken Inasal Paa & Pork BBQ Buddy Size', 'base_price' => 369],
            ['name' => 'Paa Large Buddy Size', 'base_price' => 279],
            ['name' => 'Pecho Large Buddy Size', 'base_price' => 335],
            ['name' => 'Paa Large Family Size + Palabok Family Size', 'base_price' => 873],
            ['name' => 'Pecho Large Family Size + Palabok Family Size', 'base_price' => 941],
            ['name' => 'Chicken Inasal Regular', 'base_price' => 112],
            ['name' => '6 pcs Chicken Inasal Regular Family Size', 'base_price' => 542],
            ['name' => '8 pcs Chicken Inasal Regular Family Size', 'base_price' => 733],
            ['name' => 'Fiesta Meal Paa Large', 'base_price' => 192],
            ['name' => 'Fiesta Meal Pecho Large', 'base_price' => 220],
        ];

        foreach ($chickenItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $chicken->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            // Add variants based on item name
            if (strpos($item['name'], 'Family Size') === false && strpos($item['name'], 'Trio') === false && strpos($item['name'], 'Buddy') === false) {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'Solo',
                    'price' => $item['base_price'],
                    'order' => 1,
                ]);

                if (strpos($item['name'], 'Fiesta') === false) {
                    MenuVariant::create([
                        'menu_item_id' => $menuItem->id,
                        'name' => 'With Drink',
                        'price' => $item['base_price'] + 28,
                        'order' => 2,
                    ]);
                }
            } else {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'Regular',
                    'price' => $item['base_price'],
                    'order' => 1,
                ]);
            }
        }

        // Family Fiesta Menu
        $familyFiesta = Category::create([
            'name' => 'Family Fiesta Menu',
            'slug' => 'family-fiesta',
            'description' => 'Large group celebration meals',
            'order' => 3,
        ]);

        $familyFiestaItems = [
            ['name' => 'Chicken Inasal Paa & Pork BBQ Family Fiesta', 'base_price' => 799],
            ['name' => 'Chicken Inasal Pecho & Pork BBQ Family Fiesta', 'base_price' => 989],
            ['name' => 'All Chicken Inasal Paa Family Fiesta', 'base_price' => 937],
            ['name' => 'All Chicken Inasal Pecho Family Fiesta', 'base_price' => 956],
            ['name' => 'Pork BBQ & Grilled Liempo Family Fiesta', 'base_price' => 840],
            ['name' => 'Chicken Inasal Paa & Grilled Liempo Family Fiesta', 'base_price' => 964],
            ['name' => 'Chicken Inasal Pecho & Grilled Liempo Family Fiesta', 'base_price' => 1077],
            ['name' => 'Chicken Inasal Paa & Pork BBQ Family Fiesta Bundle', 'base_price' => 912],
            ['name' => 'All Chicken Inasal Paa Family Fiesta Bundle', 'base_price' => 1050],
            ['name' => 'Pork BBQ & Grilled Liempo Family Fiesta Bundle', 'base_price' => 953],
            ['name' => 'Chicken Inasal Paa & Grilled Liempo Family Fiesta Bundle', 'base_price' => 1077],
        ];

        foreach ($familyFiestaItems as $index => $item) {
            MenuItem::create([
                'category_id' => $familyFiesta->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);
        }

        // Buddy Fiesta Menu
        $buddyFiesta = Category::create([
            'name' => 'Buddy Fiesta Menu',
            'slug' => 'buddy-fiesta',
            'description' => 'Small group celebration meals',
            'order' => 4,
        ]);

        $buddyFiestaItems = [
            ['name' => 'Chicken Inasal Paa & Pork BBQ Buddy Fiesta', 'base_price' => 459],
            ['name' => 'Chicken Inasal Pecho & Pork BBQ Buddy Fiesta', 'base_price' => 512],
            ['name' => 'All Chicken Inasal Paa Buddy Fiesta', 'base_price' => 495],
            ['name' => 'All Chicken Inasal Pecho Buddy Fiesta', 'base_price' => 577],
            ['name' => 'Pork BBQ & Grilled Liempo Buddy Fiesta', 'base_price' => 419],
            ['name' => 'Chicken Inasal Paa & Grilled Liempo Buddy Fiesta', 'base_price' => 476],
            ['name' => 'Chicken Inasal Pecho & Grilled Liempo Buddy Fiesta', 'base_price' => 532],
            ['name' => 'Pork BBQ & Grilled Liempo Buddy Fiesta Bundle', 'base_price' => 476],
            ['name' => 'Chicken Inasal Paa & Pork BBQ Buddy Fiesta Bundle', 'base_price' => 508],
            ['name' => 'All Chicken Inasal Paa Buddy Fiesta Bundle', 'base_price' => 542],
            ['name' => 'Chicken Inasal Paa & Grilled Liempo Buddy Fiesta Bundle', 'base_price' => 533],
        ];

        foreach ($buddyFiestaItems as $index => $item) {
            MenuItem::create([
                'category_id' => $buddyFiesta->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);
        }

        // Solo Fiesta Menu
        $soloFiesta = Category::create([
            'name' => 'Solo Fiesta Menu',
            'slug' => 'solo-fiesta',
            'description' => 'Individual celebration meals',
            'order' => 5,
        ]);

        $soloFiestaItems = [
            ['name' => 'Chicken Inasal Paa & 1 pc Pork BBQ Solo Fiesta', 'base_price' => 283],
            ['name' => 'Chicken Inasal Pecho & 1 pc Pork BBQ Solo Fiesta', 'base_price' => 311],
            ['name' => 'Chicken Inasal Paa Solo Fiesta', 'base_price' => 247],
            ['name' => 'Chicken Inasal Pecho Solo Fiesta', 'base_price' => 275],
            ['name' => '2 pc Pork BBQ Solo Fiesta', 'base_price' => 214],
            ['name' => 'Chicken Inasal Paa & Grilled Liempo Solo Fiesta', 'base_price' => 379],
            ['name' => 'Chicken Inasal Pecho & Grilled Liempo Solo Fiesta', 'base_price' => 407],
            ['name' => 'Grilled Liempo & 1pc Pork BBQ Solo Fiesta', 'base_price' => 283],
            ['name' => 'Grilled Liempo Solo Fiesta', 'base_price' => 247],
        ];

        foreach ($soloFiestaItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $soloFiesta->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Solo',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'With Drink',
                'price' => $item['base_price'] + 28,
                'order' => 2,
            ]);
        }

        // Halo-Halo Menu
        $haloHalo = Category::create([
            'name' => 'Halo-Halo Menu',
            'slug' => 'halo-halo',
            'description' => 'Desserts and sweet treats',
            'order' => 6,
        ]);

        $haloHaloItems = [
            ['name' => 'Extra Creamy Halo-Halo', 'base_price' => 76],
            ['name' => 'Crema de Leche Halo-Halo', 'base_price' => 76],
            ['name' => 'Treat kay Mommy – Extra Creamy Halo-Halo', 'base_price' => 102],
            ['name' => 'Strawberry Cheesecake Halo-Halo', 'base_price' => 102],
        ];

        foreach ($haloHaloItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $haloHalo->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Small',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Regular',
                'price' => $item['base_price'] + 26,
                'order' => 2,
            ]);
        }

        // Palabok Menu
        $palabok = Category::create([
            'name' => 'Palabok Menu',
            'slug' => 'palabok',
            'description' => 'Traditional Filipino noodle dishes',
            'order' => 7,
        ]);

        $palabokItems = [
            ['name' => 'Palabok Regular Size', 'base_price' => 96],
            ['name' => 'Palabok with Extra Creamy Halo-Halo Small', 'base_price' => 172],
            ['name' => 'Palabok with Crema de Leche Halo-Halo Small', 'base_price' => 172],
            ['name' => 'Palabok Regular Size with 1 PC Pork BBQ', 'base_price' => 107],
            ['name' => 'Palabok Family Size', 'base_price' => 345],
            ['name' => 'Palabok Party Size', 'base_price' => 733],
        ];

        foreach ($palabokItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $palabok->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Solo',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            if (strpos($item['name'], 'Family') === false && strpos($item['name'], 'Party') === false) {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'With Drink',
                    'price' => $item['base_price'] + 28,
                    'order' => 2,
                ]);
            }
        }

        // Grilled Liempo Menu
        $grilledLiempo = Category::create([
            'name' => 'Grilled Liempo Menu',
            'slug' => 'grilled-liempo',
            'description' => 'Grilled pork belly options',
            'order' => 8,
        ]);

        $grilledLiempoItems = [
            ['name' => 'Grilled Liempo', 'base_price' => 149],
            ['name' => 'Sizzling Liempo', 'base_price' => 162],
            ['name' => 'Grilled Liempo Family Size', 'base_price' => 568],
            ['name' => 'Grilled Liempo Buddy Size', 'base_price' => 287],
        ];

        foreach ($grilledLiempoItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $grilledLiempo->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Solo',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            if (strpos($item['name'], 'Family') === false && strpos($item['name'], 'Buddy') === false) {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'With Drink',
                    'price' => $item['base_price'] + 28,
                    'order' => 2,
                ]);
            }
        }

        // Pork BBQ Menu
        $porkBBQ = Category::create([
            'name' => 'Pork BBQ Menu',
            'slug' => 'pork-bbq',
            'description' => 'Grilled pork barbecue options',
            'order' => 9,
        ]);

        $porkBBQItems = [
            ['name' => '2 pcs Pork BBQ', 'base_price' => 112],
            ['name' => 'Pork BBQ Buddy Size', 'base_price' => 218],
            ['name' => 'Pork BBQ Family Size', 'base_price' => 537],
            ['name' => '2 pcs Pork BBQ with Peanut Sauce and Java Rice', 'base_price' => 149],
            ['name' => '1 pc Pork BBQ Ala Carte', 'base_price' => 57],
        ];

        foreach ($porkBBQItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $porkBBQ->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Solo',
                'price' => $item['base_price'],
                'order' => 1,
            ]);

            if (strpos($item['name'], 'Family') === false && strpos($item['name'], 'Buddy') === false && strpos($item['name'], 'Ala Carte') === false) {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'With Drink',
                    'price' => $item['base_price'] + 28,
                    'order' => 2,
                ]);
            }
        }

        // Lumpiang Togue Menu
        $lumpiang = Category::create([
            'name' => 'Lumpiang Togue Menu',
            'slug' => 'lumpiang-togue',
            'description' => 'Fried spring rolls',
            'order' => 10,
        ]);

        $lumiangItems = [
            ['name' => '2 pcs Lumpiang Togue', 'base_price' => 62],
            ['name' => '6 pcs Lumpiang Togue', 'base_price' => 186],
        ];

        foreach ($lumiangItems as $index => $item) {
            MenuItem::create([
                'category_id' => $lumpiang->id,
                'name' => $item['name'],
                'base_price' => $item['base_price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);
        }

        // Sisig Menu
        $sisig = Category::create([
            'name' => 'Sisig Menu',
            'slug' => 'sisig',
            'description' => 'Chopped meat dishes',
            'order' => 11,
        ]);

        $sisigItems = [
            ['name' => 'Pork Sisig', 'base_price' => 112],
            ['name' => 'Bangus Sisig', 'base_price' => 149],
            ['name' => 'Chicken Sisig', 'base_price' => 0],
            ['name' => 'Pork Sisig Family Size', 'base_price' => 302],
            ['name' => 'Bangus Sisig Family Size', 'base_price' => 385],
            ['name' => 'Chicken Sisig Family Size', 'base_price' => 0],
            ['name' => 'Pork Sisig Rice Bowl Solo', 'base_price' => 0],
        ];

        foreach ($sisigItems as $index => $item) {
            if ($item['base_price'] > 0) {
                $menuItem = MenuItem::create([
                    'category_id' => $sisig->id,
                    'name' => $item['name'],
                    'base_price' => $item['base_price'],
                    'is_active' => true,
                    'order' => $index + 1,
                ]);

                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'Solo',
                    'price' => $item['base_price'],
                    'order' => 1,
                ]);

                if (strpos($item['name'], 'Family') === false && strpos($item['name'], 'Rice Bowl') === false) {
                    MenuVariant::create([
                        'menu_item_id' => $menuItem->id,
                        'name' => 'With Drink',
                        'price' => $item['base_price'] + 28,
                        'order' => 2,
                    ]);
                }
            }
        }

        // Drinks Menu
        $drinks = Category::create([
            'name' => 'Drinks Menu',
            'slug' => 'drinks',
            'description' => 'Beverages',
            'order' => 12,
        ]);

        $drinkItems = [
            ['name' => 'Iced Red Gulaman', 'small' => 44, 'medium' => 55],
            ['name' => 'Iced Tea', 'small' => 44, 'medium' => 55],
            ['name' => 'Coke', 'small' => 44, 'medium' => 55],
            ['name' => 'Coke Zero', 'small' => 44, 'medium' => 55],
            ['name' => 'Sprite', 'small' => 44, 'medium' => 55],
            ['name' => 'Coke Regular 1.5L', 'small' => 112, 'medium' => 0],
        ];

        foreach ($drinkItems as $index => $item) {
            $menuItem = MenuItem::create([
                'category_id' => $drinks->id,
                'name' => $item['name'],
                'base_price' => $item['small'],
                'is_active' => true,
                'order' => $index + 1,
            ]);

            MenuVariant::create([
                'menu_item_id' => $menuItem->id,
                'name' => 'Small (12oz)',
                'price' => $item['small'],
                'order' => 1,
            ]);

            if ($item['medium'] > 0) {
                MenuVariant::create([
                    'menu_item_id' => $menuItem->id,
                    'name' => 'Medium (16oz)',
                    'price' => $item['medium'],
                    'order' => 2,
                ]);
            }
        }

        // Extras Menu
        $extras = Category::create([
            'name' => 'ATBP (Extras) Menu',
            'slug' => 'extras',
            'description' => 'Add-ons and side dishes',
            'order' => 13,
        ]);

        $extraItems = [
            ['name' => 'Plain Rice', 'price' => 28],
            ['name' => 'Java Rice', 'price' => 40],
            ['name' => 'Soup', 'price' => 11],
            ['name' => 'Chicken Oil', 'price' => 7],
            ['name' => 'Toyomansi', 'price' => 7],
            ['name' => 'Peanut Sauce', 'price' => 8],
            ['name' => 'Spiced Vinegar', 'price' => 8],
            ['name' => 'Mushroom Gravy', 'price' => 11],
        ];

        foreach ($extraItems as $index => $item) {
            MenuItem::create([
                'category_id' => $extras->id,
                'name' => $item['name'],
                'base_price' => $item['price'],
                'is_active' => true,
                'order' => $index + 1,
            ]);
        }
    }
}
