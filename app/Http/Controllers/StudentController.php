<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function create(Request $request)
    {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move('uploads', $filename);

        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->image = $filename;
        $student->save();
        return response()->json(['res' => "Student added successfully"]);
    }

    public function getStudents()
    {
        $students = Student::all();
        return response()->json(['students' => $students]);
    }

    public function editStudent($id)
    {
        $student = Student::where('id', $id)->first();
        return view('edit', ['student' => $student]);
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(['res' => "Student deleted successfully"]);
    }
    public function updateData(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found!'], 404);
        }

        $student->name = $request->name;
        $student->email = $request->email;

        // **File Upload Logic**
        if ($request->hasFile('file')) {
            if ($student->image && file_exists(public_path('uploads/' . $student->image))) {
                unlink(public_path('uploads/' . $student->image));
            }
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $student->image = $filename;
        }

        $student->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $students = Student::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($students);
    }
}
