<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
   function index(){
    // $text = 'omar';
    // $text .='hamdia';
    // return $text;  
    return view('dashboard.teachers.index');
   }
   function getdata(Request $request)
{
    $grades = Teacher::query();
    return DataTables::of($grades)
        ->addIndexColumn()
       ->addColumn('email', function($qur){
        return $qur->user->email;
       })->addColumn('gender', function($qur){
           if($qur->gender == 'm'){
            return 'ذكر';
           }
           return 'أنثى';
       })
       ->addColumn('qual', function ($qur){
           return $qur->getQualByCode($qur->qual);
       })
       ->addColumn('action', function ($qur) {
                      $data_attr   = ''   ;
                      $data_attr   .= 'data-id="'.$qur->id.'" ';
                      $data_attr   .= 'data-name="'.$qur->name.'" ';
                      $data_attr   .= 'data-phone="'.$qur->phone.'" ';
                      $data_attr   .= 'data-email="'.$qur->user->email.'" ';
                      $data_attr   .= 'data-spec="'.$qur->spec.'" ';
                      $data_attr   .= 'data-qual="'.$qur->qual.'" ';
                      $data_attr   .= 'data-gender="'.$qur->gender.'" ';
                      $data_attr   .= 'data-date-of-birth="'.$qur->date_of_birth.'" ';
                      $data_attr   .= 'data-hire-date="'.$qur->hire_date.'" ';
                      $data_attr   .= 'data-status="'.$qur->status.'" ';


                      $action  =   ' <div class="d-flex align-items-center gap-3 fs-6">
                                <a '. $data_attr .' href="javascript:;" class="text-warning update_btn"data-bs-target="#update-modal" data-bs-toggle="modal" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <a data-id="'. $qur->id .'" data-url="'.route('dash.teachers.delete').'" href="javascript:;" class="text-danger delete-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="bi bi-trash-fill"></i></a>
                              </div>';
    
    return $action;

        })
        
        ->make(true);
}
   public function add(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|integer|unique:teachers,phone',
        'email' => 'required|email|unique:users,email',
        'hire_date' => 'required|date',
        'qual' => 'required|in:d,b,m,dr',
        'spec' => 'required|string|max:255',
        'gender' => 'required|in:m,mf',
     ],[
        'name.required' => 'الاسم مطلوب',
        'name.string' => 'الاسم يجب أن يكون نص',
        'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
        'phone.required' => 'رقم الهاتف مطلوب',
        'phone.integer' => 'رقم الهاتف يجب أن يكون رقم صحيح',
        'phone.unique' => 'رقم الهاتف موجود مسبقاً',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صحيح',
        'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
        'hire_date.required' => 'تاريخ التعيين مطلوب',
        'hire_date.date' => 'تاريخ التعيين يجب أن يكون تاريخ صحيح',
        'qual.required' => 'المؤهل مطلوب',
        'qual.in' => 'المؤهل يجب أن يكون واحد من القيم التالية: d, b, m, dr',
        'spec.required' => 'التخصص مطلوب',
        'spec.string' => 'التخصص يجب أن يكون نص',
        'spec.max' => 'التخصص يجب أن لا يتجاوز 255 حرف',
        'gender.required' => 'الجنس مطلوب',
        'gender.in' => 'الجنس يجب أن يكون واحد من القيم التالية: m, mf',
        'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
        'date_of_birth.date' => 'تاريخ الميلاد يجب أن يكون تاريخ صحيح',

     ]);

    $user = User::create([
        'email' => $request->email,
        'password'=> Hash::make($request->phone),
    ]);

    Teacher::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'hire_date' => $request->hire_date,
        'date_of_birth' => $request->date_of_birth,
        'qual' => $request->qual,
        'spec' => $request->spec,
        'gender' => $request->gender,
        'status' => $request->status,
        'user_id' => $user->id,
    ]);

    return response()->json([
        'success' => 'تمت العملية بنجاح'
    ]);
}
 function update(Request $request)
{
    $teacher = Teacher::query()->findOrFail($request->id);
    $user = User::query()->findOrFail($teacher->user_id);

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|integer|unique:teachers,phone',
        'email' => 'required|email|unique:users,email',
        'hire_date' => 'required|date',
        'qual' => 'required|in:d,b,m,dr',
        'spec' => 'required|string|max:255',
        'gender' => 'required|in:m,mf',
     ],[
        'name.required' => 'الاسم مطلوب',
        'name.string' => 'الاسم يجب أن يكون نص',
        'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
        'phone.required' => 'رقم الهاتف مطلوب',
        'phone.integer' => 'رقم الهاتف يجب أن يكون رقم صحيح',
        'phone.unique' => 'رقم الهاتف موجود مسبقاً',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صحيح',
        'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
        'hire_date.required' => 'تاريخ التعيين مطلوب',
        'hire_date.date' => 'تاريخ التعيين يجب أن يكون تاريخ صحيح',
        'qual.required' => 'المؤهل مطلوب',
        'qual.in' => 'المؤهل يجب أن يكون واحد من القيم التالية: d, b, m, dr',
        'spec.required' => 'التخصص مطلوب',
        'spec.string' => 'التخصص يجب أن يكون نص',
        'spec.max' => 'التخصص يجب أن لا يتجاوز 255 حرف',
        'gender.required' => 'الجنس مطلوب',
        'gender.in' => 'الجنس يجب أن يكون واحد من القيم التالية: m, mf',
        'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
        'date_of_birth.date' => 'تاريخ الميلاد يجب أن يكون تاريخ صحيح',

     ]);

    $user->update(['email' => $request->email]);

    $teacher->update([
        'name' => $request->name,
        'qual' => $request->qual,
        'spec' => $request->spec,
        'gender' => $request->gender,
        'status' => $request->status,
        'phone' => $request->phone,
        'hire_date' => $request->hire_date,
        'date_of_birth' => $request->date_of_birth,
    ],[
        'name.required' => 'الاسم مطلوب',
        'phone.required' => 'رقم الهاتف مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'hire_date.required' => 'تاريخ التعيين مطلوب',
        'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
        'qual.required' => 'المؤهل مطلوب',
        'spec.required' => 'التخصص مطلوب',
        'gender.required' => 'الجنس مطلوب',

    ]);

    return response()->json([
        'success' => 'تم تحديث بيانات المعلم بنجاح'
    ], 200, [], JSON_UNESCAPED_UNICODE);
}

    function delete(Request $request){

    }
        
    }









