<?php

namespace App\Http\Controllers\Stages;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Grade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stage;

class StageController extends Controller
{
    function index()
    {
        return view('dashboard.grades.index');
    }
    
function getdata(Request $request)
{
    $grades = Grade::with('stage');
    return DataTables::of($grades)
        ->addIndexColumn()
        ->addColumn('action', function () {
            return '<div  data-bs-toggle="modal" data-bs-target="#sectionModal" class="d-flex align-items-center gap-3 fs-6">
                        <a href="javascript:;" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fadeIn animated bx bx-message-square-add"></i></a>
                    </div>';
        })
        ->addColumn('stage', function ($qur) {
            return $qur->stage ? $qur->stage->name : '';
        })
        ->addColumn('status', function ($qur) {
            return $qur->status == 'active' ? 'مفعل' : 'غير مفعل';
        })
        ->rawColumns(['action'])
        ->make(true);
        
    }    
    
    function create()
    {
        $stages = Stage::all();
        return view('dashboard.grades.create', compact('stages'));
    }
    function add(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'=> 'required',
            'tag'=> 'required', 
            'stage'=> 'required', 
        ] ,
        [
            'name.required' => 'الاسم مطلوب',
            'status.required' => 'الحالة مطلوب',
            'stage.required' => 'المرحلة مطلوبة',
            'tag.required' => 'المرحلة مطلوبة',
             
        ]);


        $stage_id = Stage::getIdByTag($request->stage);
        $status = Grade::getStatusByCod($request->status);
        $grade = Grade::query()->where('tag', $request->tag)->first();

        if ($grade) {
    $grade->update([
        'name' => $request->name,
        'stage_id' => $stage_id, 
        'status' => $status,
        'tag' => $request->tag,
    ]);
} else {
    Grade::create([
        'name' => $request->name,
        'stage_id' => $stage_id, 
        'status' => $status,
        'tag' => $request->tag,
    ]);
}
return response()->json(['message' => 'تم اضافة المرحلة بنجاح']);
// ...existing code...

       
    }
    function getactive(){
        $active = Grade::query()->where('status', 'active')->pluck('tag');
        return response()->json([
            'tags' => $active,
        ]);
    }
}
