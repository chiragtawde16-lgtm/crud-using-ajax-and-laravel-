<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required'
    ]);

    $student = Student::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $student
    ]);
}

    public function fetch()
{
    return Student::orderBy('id', 'desc')->get();
}

    public function destroy($id)
    {
        Student::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }
}