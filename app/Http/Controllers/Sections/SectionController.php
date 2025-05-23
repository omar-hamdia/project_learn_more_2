<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    function index(){
        return view('dashboard.sections.index');

    }
    function getdata(Request $request)
{
    $grades = Section::query();
    return DataTables::of($grades)
        ->addIndexColumn()
        ->addColumn('name', function ($qur) {
            return 'الشعبة ' . $qur->name;
        })
       ->addColumn('action', function ($qur) {
    $section = Section::where('status', 'active')->orderBy('id', 'desc')->first();
    $sectiondisable = Section::where('status', 'inactive')->first();
    
    // للقسم النشط (active)
    if (@$section->id == $qur->id) {
        return '<div data-id="' . $qur->id . '" class="form-check form-switch active-section-sw">
                 <input data-status="inactive" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
               </div>';
    }
    
    // للقسم غير النشط (inactive) - باستخدام @$ كما طلبت
    if (@$sectiondisable->id == $qur->id) {
        return '<div data-status="active" data-id="' . $qur->id . '" class="form-check form-switch active-section-sw">
                 <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
               </div>';
    }
    
    return ' - ';

        })
        ->addColumn('status', function ($qur) {
            return $qur->status == 'active' ? 'مفعل' : 'غير مفعل';
        })
        ->make(true);
}
    function add(Request $request){
        //  dd($request->all());
        $newcount = (int)$request->count_section;
        $currentCount = Section::count();
        if($newcount > $currentCount){
        //    dd($newcount - $currentCount);
           for($i = $currentCount +1 ; $i <= $newcount; $i++){
                Section::create([
                    'name'=> $i ,
                    'status'=> 'active', 

                ]);
                $sectionInActive = Section::query()->where('status', 'inactive')->get();
                foreach($sectionInActive as $s){
                    $s->update([
                        'status'=> 'active',
                    ]);
                }
                
                }
           }elseif($newcount < $currentCount){
            $limit = $currentCount - $newcount;
            $lastSections = Section::query()->orderBy('id', 'desc')->limit($limit)->get(); 
            foreach($lastSections as $l){
                $l->update([
                    'status'=> 'inactive',
                ]);
            }
            }elseif($newcount == $currentCount){
                $sectionInActive = Section::query()->where('status', 'inactive')->get();
                foreach($sectionInActive as $e){
                    $e->update([
                        'status'=> 'active',
                    ]);
                }
        }

             return response()->json([
            'success' => 'تمت العملية بنجاح'
        ]);
        }
       function changestatus(Request $request)
    {
        $section = Section::query()->findOrFail($request->id);

        if ($request->status == 'active') {
            $section->update([
                'status' => 'active'
            ]);
        } else {
            $section->update([
                'status' => 'inactive'
            ]);
        }



        return response()->json([
            'success' => 'تمت العملية بنجاح'
        ]);
    }
         
    }
