<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\categories;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        categories::create([
        'category_name' => 'Elektronik', 
        'description' => 'Komputer (PC/Laptop) yang difungsikan sebagai server',
        ]);

        categories::create([
        'category_name' => 'Makanan', 
        'description' => 'Nasi/Burger/Pizza yang difungsikan sebagai makanan',
        ]);


        //  foreach ($data as $item) {

        //     categories::create($item);
    }
}
