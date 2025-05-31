<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    function index()
    {
        $teachers = Teacher::all();
        $grades = Grade::all();
        return view('dashboard.subjects.index', compact('teachers', 'grades'));
    }

   public function getdata(Request $request)
{
    $subjects = Subject::query();
    
    return DataTables::of($subjects)
        ->addIndexColumn()
        ->addColumn('teacher', function($qur) {
            return $qur->teacher->name;
        })
        ->addColumn('grade', function($qur) {
             return $qur->grade->name;
        })
        ->addColumn('book', function ($qur) {
                return '    <a href="'.route('dash.subjects.download' , $qur->book).'" class="btn btn-primary btn-sm"
                            >
                            كتاب "' . $qur->title . '"  ' . $qur->grade->name . '
                        </a>';
            })
            ->addColumn('lectures', function ($qur) {
                return '    <a href="'.route('dash.subjects.lectures' , $qur->id).'" class="btn btn-primary btn-sm"
                            >عرض جميع المحاضرات</a>';
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
        ->rawColumns(['book', 'action', 'lectures'])
        ->make(true);
}
 
    function add(Request $request)
    {


        $request->validate([
        'title'   => ['required', 'string', 'max:255'],
        'teacher'  => ['required', 'exists:teachers,id'],
        'grade'  => ['required',  'exists:grades,id'],
        'book' => ['required', 'mimes:pdf', 'max:10240'], 
        'title.required' => 'عنوان المادة مطلوب.',
        'title.string' => 'عنوان المادة يجب أن يكون نصاً.',
        'teacher.required' => 'يرجى اختيار المدرس.',
        'teacher.exists' => 'المعلم المحدد غير موجود.',
        'book.required' => 'يرجى رفع كتاب المادة.',
        'book.mimes' => 'يجب أن يكون الكتاب بصيغة PDF فقط.',
        'book.max' => 'أقصى حجم للكتاب هو 5 ميجابايت.',
        'grade.required' => 'يرجى إدخال المرحلة الدراسية.',
    ]);

        $name = 'LearnSchool_' . time() . '_' . rand() . '.' . $request->file('book')->getClientOriginalExtension();
       $request->file('book')->move(public_path('uploads\books'),$name);
       $grade = Grade::query()->where('tag', $request->grade)->first();
    

    Subject::create([
        'title' => $request->title,
        'teacher_id' => $request->teacher,
        'grade_id' => $request->grade, 
        'book' => $name,
    ]);

    return response()->json([
        'success' => 'تمت العملية بنجاح'
    ]);
}
 function download($filename) {
        $path = public_path('uploads/books/' . $filename );

        return response()->download($path);
    }
    function lectures($id) {
        $subject = Subject::query()->findOrFail($id);
        return view('dashboard.subjects.lectures' , compact('subject'));
    }

     function getdataLectures(Request $request)
    {
              //dd($request->all());
        $grades = Lecture::query()->where('subject_id' , $request->id);
       //dd($grades);
        return DataTables::of($grades)
            ->filter(function ($qur) use ($request) {
                if($request->get('title')){
                    // like %...% , %.. , ..%
                 $qur->where('title' , 'like' , '%' .  $request->get('title') . '%');
                }
            })
            ->addIndexColumn()
            ->addColumn('subject', function ($qur) {
                return $qur->subject->title;
            })
            ->addColumn('teacher', function ($qur) {
                return $qur->teacher->name;
            })
            ->addColumn('link', function ($qur) {
                return '<a class="btn btn-info btn-sm" target="_blank" href="'. $qur->link .'">رابط المحاضرة</a>';
            })
            ->rawColumns(['status', 'action', 'gender' , 'link'])
            ->make(true);
    }

}