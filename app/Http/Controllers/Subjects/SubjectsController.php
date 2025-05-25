<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
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
    $subjects = Subject::with(['teacher', 'grade'])->select('subjects.*');
    
    return DataTables::of($subjects)
        ->addIndexColumn()
        ->addColumn('grade', function($subject) {
            return $subject->grade->name ?? 'غير محدد';
        })
        ->addColumn('teacher', function($subject) {
            return $subject->teacher->name ?? 'غير محدد';
        })
        ->addColumn('book', function($subject) {
            return $subject->book ? '<a href="'.asset('storage/'.$subject->book).'" target="_blank">عرض الكتاب</a>' : 'لا يوجد';
        })
        ->addColumn('action', function($subject) {
            $buttons = '<div class="d-flex gap-2">';
            $buttons .= '<button class="btn btn-sm btn-warning update-btn" 
                        data-id="'.$subject->id.'"
                        data-title="'.$subject->title.'"
                        data-grade="'.$subject->grade_id.'"
                        data-teacher="'.$subject->teacher_id.'"
                        data-book="'.$subject->book.'">
                        <i class="bi bi-pencil"></i></button>';
            
            if($subject->status == 'active') {
                $buttons .= '<button class="btn btn-sm btn-danger delete-btn" 
                            data-id="'.$subject->id.'">
                            <i class="bi bi-trash"></i></button>';
            } else {
                $buttons .= '<button class="btn btn-sm btn-success activate-btn" 
                            data-id="'.$subject->id.'">
                            <i class="bi bi-check"></i></button>';
            }
            
            $buttons .= '</div>';
            return $buttons;
        })
        ->rawColumns(['book', 'action'])
        ->make(true);
}

    function add(Request $request)
    {


        $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'teacher'  => ['required', 'exists:teachers,id'],
            'grade'  => ['required',  'exists:grades,id'],
            'book'   => ['required', 'mimes:pdf', 'max:5120'],
        ],  [
            'title.required' => 'عنوان المادة مطلوب.',
            'title.string' => 'عنوان المادة يجب أن يكون نصاً.',
            'teacher.required' => 'يرجى اختيار المدرس.',
            'teacher.exists' => 'المعلم المحدد غير موجود.',
            'book.required' => 'يرجى رفع كتاب المادة.',
            'book.mimes' => 'يجب أن يكون الكتاب بصيغة PDF فقط.',
            'book.max' => 'أقصى حجم للكتاب هو 5 ميجابايت.',
            'grade.required' => 'يرجى إدخال المرحلة الدراسية.',
            'grade.string' => 'المرحلة الدراسية يجب أن تكون نصاً.',
        ]);


        $name = 'LearnSchool_' . time() . '_' . rand() . '.' . $request->file('book')->getClientOriginalExtension();
        $request->file('book')->move(public_path('uploads\books'), $name);

        $grade = Grade::query()->where('tag', $request->grade)->first();

        Subject::create([
            'title' => $request->title,
            'teacher_id' => $request->teacher,
            'grade_id' => $grade->id,
            'book' => $name,
        ]);

        return response()->json([
            'success' => 'تمت العملية بنجاح'
        ]);
    }
}