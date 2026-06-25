<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function uploadExcel(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json([
            'message' => 'No file selected'
        ], 400);
    }

    $file = $request->file('file');

    // Original file name
    $fileName = $file->getClientOriginalName();

    // Check if file already exists
    if (file_exists(public_path('uploads/' . $fileName))) {
        return response()->json([
            'message' => 'This file is already uploaded'
        ], 400);
    }

    // Save file
    $file->move(public_path('uploads'), $fileName);

    return response()->json([
        'message' => 'File uploaded successfully'
    ]);
}
}
