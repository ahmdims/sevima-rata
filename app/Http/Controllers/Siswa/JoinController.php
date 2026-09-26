<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class JoinController extends Controller
{
    public function create(Request $request)
    {
        return view('siswa.join', ['code' => $request->query('kode')]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:12'],
            'student_name' => ['required', 'string', 'min:2', 'max:40'],
        ], [], ['code' => 'kode kelas', 'student_name' => 'nama panggilan']);

        $assessment = Classroom::findByCode($data['code'])?->activeAssessment;

        if (! $assessment) {
            return back()->withInput()->withErrors([
                'code' => 'Kode kelas tidak ditemukan atau belum ada asesmen aktif. Coba cek lagi ke gurumu.',
            ]);
        }

        $attempt = $assessment->attempts()->create(['student_name' => trim($data['student_name'])]);
        $request->session()->push('attempts', $attempt->id);

        return redirect()->route('siswa.kerjakan', $attempt);
    }
}
