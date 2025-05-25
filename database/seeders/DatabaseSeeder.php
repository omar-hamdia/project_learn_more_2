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
        Stage::firstOrCreate(['name' => 'المرحلة الابتدائية'], ['tag' => 'p']);
        Stage::firstOrCreate(['name' => 'المرحلة الاعدادية'], ['tag' => 'm']);
        Stage::firstOrCreate(['name' => 'المرحلة الثانوية'], ['tag' => 'h']);
        $stagep =Stage::getIdByTag('h');
       $parent = Grade::create([
          'p' => [ // ابتدائي
        ['name' => 'الصف الأول الابتدائي', 'tag' => 'p1'],
        ['name' => 'الصف الثاني الابتدائي', 'tag' => 'p2'],
        ['name' => 'الصف الثالث الابتدائي', 'tag' => 'p3'],
        ['name' => 'الصف الرابع الابتدائي', 'tag' => 'p4'],
        ['name' => 'الصف الخامس الابتدائي', 'tag' => 'p5'],
        ['name' => 'الصف السادس الابتدائي', 'tag' => 'p6'],
    ],
    'm' => [ // إعدادي
        ['name' => 'الصف الأول الإعدادي', 'tag' => 'm1'],
        ['name' => 'الصف الثاني الإعدادي', 'tag' => 'm2'],
        ['name' => 'الصف الثالث الإعدادي', 'tag' => 'm3'],
    ],
    'h' => [ // ثانوي
        ['name' => 'الصف الأول الثانوي', 'tag' => 'h1'],
        ['name' => 'الصف الثاني الثانوي', 'tag' => 'h2'],
        ['name' => 'الصف الثالث الثانوي', 'tag' => 'h3'],
    ]
        ]);

        foreach ($parent->p as $grade) {
            Grade::create([
                'name' => $grade['name'],
                'tag' => $grade['tag'],
                'stage_id' => Stage::getIdByTag('p')
            ]);
        }

        foreach ($parent->m as $grade) {
            Grade::create([
                'name' => $grade['name'],
                'tag' => $grade['tag'],
                'stage_id' => Stage::getIdByTag('m')
            ]);
        }

        foreach ($parent->h as $grade) {
            Grade::create([
                'name' => $grade['name'],
                'tag' => $grade['tag'],
                'stage_id' => Stage::getIdByTag('h')
            ]);
        }



    }
}
