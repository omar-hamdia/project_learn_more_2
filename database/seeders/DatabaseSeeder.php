<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Section;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


    // التحقق من وجود البيانات قبل إضافتها
        // Stage::firstOrCreate(['name' => 'المرحلة الابتدائية'], ['tag' => 'p']);
        // Stage::firstOrCreate(['name' => 'المرحلة الاعدادية'], ['tag' => 'm']);
        // Stage::firstOrCreate(['name' => 'المرحلة الثانوية'], ['tag' => 'h']);
        // $stagep =Stage::getIdByTag('h');
       $parent = Section::create([
    'name' => 'Parent Section',
    'section_id' => null
]);

Section::create([
    'name' => '7',
    'section_id' => $parent->id
]);
    }
}
