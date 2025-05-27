<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
      public function run(): void
{
    \App\Models\User::create([
        'email' => 'teacher@omar.com',
        'password' => Hash::make('567557774'),
    ]);
}
 }

