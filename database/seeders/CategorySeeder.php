<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                "name" => "Dogs",
            ],
            [
                "name" => "Cats",
            ],
            [
                "name" => "Horses",
            ],
            [
                "name" => "Dolphins",
            ],
            [
                "name" => "Sharks",
            ],
            [
                "name" => "Chimps",
            ],
        ];

        foreach ($categories as $key => $category) {

            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                ]
            );
        }
    }
}
