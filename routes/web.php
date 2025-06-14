<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stages\StageController;
use App\Models\Stage;
use App\Models\User;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\Sections\SectionController;
use App\Http\Controllers\Teachers\TeacherController;
use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\Lectures\LectureController;
use App\Http\Controllers\Students\StudentsController;
Route::get('/', function () {
    return view('welcome');
});

// لوحة التحكم الرئيسية تحت /learnschool/dashboard
Route::prefix('learnschool')->group(function () {
    Route::prefix('dashboard')->middleware(['auth'])->name('dash.')->group(function () {
        Route::middleware('admin')->group(function(){
        // dashboard/teacher/
          Route::prefix('grades')->controller(GradeController::class)->name('grade.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::get('/getactive', 'getactive')->name('getactive');
            Route::get('/getactivesection', 'getactivesection')->name('getactive.section');
            Route::get('/getactivestage', 'getactivestage')->name('getactive.stage');
            Route::post('/add', 'add')->name('add');
            Route::post('/changemaster', 'changemaster')->name('changemaster');
            Route::post('/addsection', 'addsection')->name('addsection');
            Route::get('/create', 'create')->name('create');
        });

         Route::prefix('teachers')->middleware('teacher')->controller(TeacherController::class)->name('teachers.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::post('/add', 'add')->name('add');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/active', 'active')->name('active');
        });

         Route::prefix('lectures')->controller(LectureController::class)->name('lecture.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::post('/add', 'add')->name('add');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/active', 'active')->name('active');
        });

         Route::prefix('subjects')->controller(SubjectController::class)->name('subjects.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::get('/getdata/lectures', 'getdatalectures')->name('getdata.lectures');
            Route::post('/add', 'add')->name('add');
            Route::get('/lectures/{id}', 'lectures')->name('lectures');
            Route::get('/download/{filename}', 'download')->name('download');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/active', 'active')->name('active');
        });

         Route::prefix('students')->controller(StudentsController::class)->name('student.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::get('/getdata/lectures', 'getdatalectures')->name('getdata.lectures');
            Route::post('/add', 'add')->name('add');
            Route::post('/import', 'import')->name('import');
            Route::get('/export', 'export')->name('export');
            Route::get('/lectures/{id}', 'lectures')->name('lectures');
            Route::get('/download/{filename}', 'download')->name('download');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/active', 'active')->name('active');
        });

        Route::prefix('sections')->controller(SectionController::class)->name('section.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/getdata', 'getdata')->name('getdata');
            Route::post('/add', 'add')->name('add');
            Route::post('/changestatus', 'changestatus')->name('changestatus');
        });
        });
        // dashboard/teacher/lectures
        Route::prefix('teachers')->name('teacher.')->group(function(){
            Route::prefix('lectures')->controller(LectureController::class)->name('lecture')->group(function(){
                Route::get('/', 'index')->name('.index');
                Route::get('/getdata', 'getdata')->name('getdata');

            });
        });
        });
       
    });


// إعادة توجيه /dashboard للمسار الصحيح داخل لوحة التحكم
Route::get('/dashboard', function () {
    return redirect()->route('dash.grade.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// إعدادات الملف الشخصي للمستخدم
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// مسارات تسجيل الدخول والتسجيل
require __DIR__.'/auth.php';
