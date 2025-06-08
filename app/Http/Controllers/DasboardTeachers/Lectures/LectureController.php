<?php

namespace App\Http\Controllers\DasboardTeachers\Lectures;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;
use Yajra\DataTables\Facades\DataTables;

class LectureController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard_teachers.lectures.index');
    }

    public function getdata(Request $request)
    {
        $user = Auth::user();

        $grades = Lecture::query()->where('teacher_id', $user->id);

        return DataTables::of($grades)
            ->filter(function ($qur) use ($request) {
                if ($request->get('name')) {
                    $qur->where('name', 'like', '%' . $request->get('name') . '%');
                }

                if ($request->get('phone')) {
                    $qur->where('phone', 'like', '%' . $request->get('phone') . '%');
                }
            })
            ->addIndexColumn()
            ->addColumn('link', function ($qur) {
                return '<a class="btn btn-info btn-sm" target="_blank" href="' . $qur->link . '">رابط المحاضرة</a>';
            })
            ->addColumn('action', function ($qur) {
                $action = '';
                $action .= '<div class="d-flex align-items-center gap-3 fs-6">';

                $action .= '<a data-id="' . $qur->id . '" data-bs-toggle="modal" data-bs-target="#update-modal" class="text-warning update_btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit info" aria-label="Edit"><i class="bi bi-pencil-fill "></i></a>';

                $action .= '<a data-id="' . $qur->id . '" data-url="' . route('dash.lecture.delete') . '" class="text-danger delete-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" aria-label="Delete"><i class="bi bi-trash-fill"></i></a>';

                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['action', 'link'])
            ->make(true);
    }
}
