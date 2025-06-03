<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

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
    // if (!\App\Models\User::where('email', 'teacher@omar.com')->exists()) {
    //     \App\Models\User::create([
    //         'email' => 'teacher@omar.com',
    //         'password' => Hash::make('567557774'),
    //     ]);
    // }
    
        $grades = [];

        // المرحلة الابتدائية: p1 إلى p6
        for ($i = 1; $i <= 6; $i++) {
            $grades[] = [
                'name' => 'الصف ' . $i,
                'tag' => 'p' . $i,
                'stage_id' => 1, // تأكد أن stage_id=1 تشير للابتدائية
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // المرحلة الإعدادية: m7 إلى m9
        for ($i = 7; $i <= 9; $i++) {
            $grades[] = [
                'name' => 'الصف ' . $i,
                'tag' => 'm' . $i,
                'stage_id' => 2, // تأكد أن stage_id=2 تشير للإعدادية
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // المرحلة الثانوية: h10 إلى h12
        for ($i = 10; $i <= 12; $i++) {
            $grades[] = [
                'name' => 'الصف ' . $i,
                'tag' => 'h' . $i,
                'stage_id' => 3, // تأكد أن stage_id=3 تشير للثانوية
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('grades')->insert($grades);
    }

}