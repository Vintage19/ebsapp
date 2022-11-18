<?php

use Illuminate\Database\Seeder;
use App\Categories;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Categories::truncate();


        // array of existing categories
       $category_arr = array('Category 1'=>'category_1',
                            'Category 2'=>'category_2',
                            'Category 3'=>'category_3',
                            'Category 4'=>'category_4',
                            'Category 5'=>'category_5',);

        // And now, let's create a few categories in our database:
        foreach ($category_arr as $cat_name => $cat_url) {
            Categories::create([
                'name' => $cat_name,
                'url' => $cat_url,
            ]);
        }
    }
}
