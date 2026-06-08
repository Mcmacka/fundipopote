<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fundi Umeme',       'icon' => 'bolt',        'description' => 'Matatizo ya umeme nyumbani na ofisini'],
            ['name' => 'Fundi Mabomba',     'icon' => 'wrench',      'description' => 'Mabomba, maji na mifumo ya maji'],
            ['name' => 'Fundi Friji',       'icon' => 'snowflake',   'description' => 'Friji, AC na vifaa vya baridi'],
            ['name' => 'Fundi Simu',        'icon' => 'phone',       'description' => 'Kurekebisha simu na vifaa vya kielektroniki'],
            ['name' => 'Fundi Magari',      'icon' => 'car',         'description' => 'Matengenezo ya magari na pikipiki'],
            ['name' => 'Useremala',         'icon' => 'hammer',      'description' => 'Samani, milango na kazi za mbao'],
            ['name' => 'Upigaji Rangi',     'icon' => 'paint-brush', 'description' => 'Kupiga rangi nyumba na ofisi'],
            ['name' => 'Ufundi Kompyuta',   'icon' => 'laptop',      'description' => 'Kurekebisha kompyuta na mitandao'],
            ['name' => 'Ujenzi',            'icon' => 'building',    'description' => 'Ujenzi na ukarabati wa majengo'],
            ['name' => 'Usafi wa Nyumba',   'icon' => 'home',        'description' => 'Huduma za usafi wa nyumba na ofisi'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'        => $category['name'],
                'slug'        => Str::slug($category['name']),
                'icon'        => $category['icon'],
                'description' => $category['description'],
                'is_active'   => true,
            ]);
        }

        $this->command->info('Categories za FundiPopote zimewekwa!');
    }
}
