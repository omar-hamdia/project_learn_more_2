<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
class StudentsController extends Controller
{
    function index()
    {
        $grades = Grade::all();
        $sections = Section::all();
        return view('dashboard.students.index', compact('grades', 'sections'));
    }

  public function getdata(Request $request)
{
    $subjects = Student::with(['grade', 'section', 'user']);

    return \DataTables::of($subjects)
        ->addIndexColumn()
        ->addColumn('grade', function($qur) {
            return $qur->grade ? $qur->grade->name : '';
        })
        ->addColumn('section', function($qur) {
            return $qur->section ? $qur->section->name : '';
        })
        ->addColumn('email', function($qur) {
            return $qur->user->email  ;
        })->addColumn('gender', function ($qur) {
                if ($qur->gender == 'm') {
                    return '<span class="badge bg-info text-white">ذكر</span>';
                }
                return '<span class="badge text-white" style="background-color:#c74375;">انثى</span>';
            })
        ->addColumn('action', function($qur) {
            $data_attr = ' ';
            $action = '';
            $action .= '<div class="d-flex align-items-center gap-3 fs-6">';
            $action .= '<a ' . $data_attr . ' data-bs-toggle="modal" data-bs-target="#update-modal" class="text-warning update_btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Edit"><i class="bi bi-pencil-fill "></i></a>';
            $action .= '     <a data-id="' . $qur->id . '"  data-url="' . route('dash.subjects.delete') . '" class="text-danger delete-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="bi bi-trash-fill"></i></a>';
            $action .= '</div>';
            return $action;
        })
        ->rawColumns(['action', 'gender'])
        ->make(true);

}
function add(Request $request)
    {

        // dd($request->all());
        $request->validate([
        'first_name'     => ['required', 'string', 'max:255'],
        'parent_name'    => ['required', 'string', 'max:255'],
        'last_name'      => ['required', 'string', 'max:255'],
        'email'          => ['required', 'email', 'max:255'],
        'parent_phone'   => ['required', 'string', 'max:255'],
        'date_of_birth'  => ['required', 'date'],
        'gender'         => ['required', 'in:m,fm'],
        'grade'          => ['required', 'exists:grades,id'],
        'section'        => ['required', 'exists:sections,id'],
    ],[
        'first_name.required' => 'الاسم الأول مطلوب',
        'parent_name.required' => 'اسم ولي الأمر مطلوب',
        'last_name.required' => 'الاسم الأخير مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
        'parent_phone.required' => 'رقم ولي الأمر مطلوب',
    ]);

    
    $user = User::create([
        'email' => $request->email,
        'password'=> Hash::make('1234'),
    ]);

    $grade = Grade::find($request->grade);
    $section = Section::find($request->section);

    Student::create([
        'first_name' => $request->first_name,
        'parent_name' => $request->parent_name,
        'last_name' => $request->last_name,
        'parent_phone' => $request->parent_phone,
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'grade_id' => $grade->id,
        'section_id' => $section->id,
        'user_id' => $user->id,
        
    ]);

    return response()->json([
        'success' => 'تمت العملية بنجاح'
    ]);
}
 
public function import(Request $request)
{
    if (!$request->hasFile('excel')) {
        return response()->json(['error' => 'لم يتم رفع ملف الإكسل'], 400);
    }
    $file = $request->file('excel');
    $name = 'LearnSchool_' . time() . '_' . rand() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads/files/excel'), $name);
    $path = public_path('uploads/files/excel') . DIRECTORY_SEPARATOR . $name;

   SimpleExcelReader::create($path)->getRows()->each(function (array $row) use (&$errors) {
        $val = Validator::make($row, [
            'email' => 'required|email',

        ]); 
        if($val->fails()){
            Log::warning("لقد قمت بتجاوز هذا السطر بسبب الاخطاء" . $row);
            return;
        }
            
        $user = User::query()->where('email', $row['email'])->first();
        if(!$user){
            $password = Str::random(10);
            $user =  User::create([
                'email' => $row['email'],
                'password' => Hash::make($password),
            ]);
        }

        $grade = Grade::query()->where('tag', $row['grade'])->first();
        $section = Section::query()->where('name', $row['section'])->first();
        if (!$grade || !$section) {
            $errors[] = $row;
            return;
        }

        Student::query()->updateOrCreate(['user_id' => $user->id],[
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'parent_name' => $row['parent_name'],
            'parent_phone' => $row['parent_phone'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth'],
            'grade_id' => $grade->id,
            'section_id' => $section->id,
        ]);
    });

        
    
}
}






