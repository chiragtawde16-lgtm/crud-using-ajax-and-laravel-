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
    'email' => 'required|email|unique:students,email',
    'phone' => 'required|digits:10|unique:students,phone'
]);

    $student = Student::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return response()->json([
        'status' => 'success'
    ]);
}

    public function fetch()
{
    return Student::whereNull('deleted_at')
        ->orderBy('id', 'desc')
        ->get();
}
    public function destroy($id)
    {
        Student::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:students,email,'.$id,
        'phone' => 'required|digits:10|unique:students,phone,'.$id
    ]);

    $student = Student::find($id);

    $student->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return response()->json([
        'status' => 'success'
    ]);
}
public function show($id)
{
    return Student::find($id);
}
}