<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function uploadExcel(Request $request)
    {
        // check file
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file found'], 400);
        }

        $file = $request->file('file');

        // file name
        $name = time() . '.' . $file->getClientOriginalExtension();

        // save in public/uploads folder
        $file->move(public_path('uploads'), $name);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file' => $name
        ]);
    }
}
