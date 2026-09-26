<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with(['assessments' => fn ($query) => $query->withCount([
            'attempts',
            'attempts as finished_count' => fn ($q) => $q->whereNotNull('finished_at'),
        ])])->latest()->get();

        return view('guru.index', compact('classrooms'));
    }

    public function storeClassroom(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'subject' => ['required', 'string', 'max:60'],
            'grade' => ['required', 'string', 'max:30'],
        ], [], ['name' => 'nama kelas', 'subject' => 'mata pelajaran', 'grade' => 'jenjang']);

        $classroom = Classroom::create($data);

        return redirect()->route('guru.index')->with('status', "Kelas {$classroom->name} dibuat. Kode kelas: {$classroom->code}");
    }
}
