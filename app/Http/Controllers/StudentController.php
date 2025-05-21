<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Generate nim: 10XXYYUUU
    // XX = kode program studi, Y = 2 digit angkatan, UUU = nomor urut terakhir + 1

    private function generateNim($program_studi, $angkatan)
    {
        // Contoh mapping kode program studi, sesuaikan sendiri
        $kodeProdi = [
            'Kimia' => '11',
            'Ilmu Komputer' => '12',
            'Matematika' => '13',
            'Teknik Industri' => '21',
        ];

        $kode = $kodeProdi[$program_studi] ?? '00';

        $tahun = substr($angkatan, -2);

        // Cari nomor urut terakhir nim dengan kode dan tahun sama
        $lastStudent = Student::where('nim', 'like', "10{$kode}{$tahun}%")
            ->orderBy('nim', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->nim, 6); // ambil 3 digit terakhir
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $numberPadded = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "10{$kode}{$tahun}{$numberPadded}";
    }

    public function index()
    {
        return Student::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'program_studi' => 'required|string',
            'angkatan' => 'required|digits:4|integer',
        ]);

        $nim = $this->generateNim($request->program_studi, $request->angkatan);

        $student = Student::create([
            'nim' => $nim,
            'name' => $request->name,
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return $student;
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string',
            'program_studi' => 'sometimes|required|string',
            'angkatan' => 'sometimes|required|digits:4|integer',
        ]);

        // Jika program_studi atau angkatan berubah, generate ulang nim
        if ($request->has('program_studi') || $request->has('angkatan')) {
            $program_studi = $request->program_studi ?? $student->program_studi;
            $angkatan = $request->angkatan ?? $student->angkatan;
            $nim = $this->generateNim($program_studi, $angkatan);
            $student->nim = $nim;
        }

        $student->fill($request->only(['name', 'program_studi', 'angkatan']));
        $student->save();

        return response()->json($student);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(null, 204);
    }
}
