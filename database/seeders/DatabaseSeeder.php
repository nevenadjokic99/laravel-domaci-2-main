<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\Music;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(50)->create();
        $categories = Category::factory()->count(10)->create();

        for ($i = 0; $i < 70; $i++) {
            Music::factory()->create([
                'user_id' => $users[rand(0, sizeof($users) - 1)],
                'category_id' => $categories[rand(0, sizeof($categories) - 1)]
            ]);
        }
    }
}
