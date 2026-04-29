<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		//data awal kamus
		$this->call([
            KamberaDictionarySeeder::class,
        ]);
		
		//data awal artike
		$this->call([
            ArticleSeeder::class,
        ]);
		
        // User::factory(10)->create();

        //User::factory()->create([
         //   'name' => 'Test User',
         //   'email' => 'test@example.com',
       // ]);
    }
}
