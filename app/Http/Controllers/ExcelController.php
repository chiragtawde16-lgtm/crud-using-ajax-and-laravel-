<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function uploadExcel(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file selected'], 400);
        }

        $file = $request->file('file');

        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json(['message' => 'Only Excel Files Allowed'], 400);
        }

        // unique name
        $fileName = time() . '_' . $file->getClientOriginalName();

        $filePath = public_path('uploads/' . $fileName);
        $file->move(public_path('uploads'), $fileName);

        // 🔥 real empty check (important fix)
        $data = Excel::toArray(new UsersImport, $filePath);

        if (empty($data) || empty($data[0])) {
            return response()->json([
                'message' => 'Empty Excel file not allowed'
            ], 400);
        }
        $fileName = $file->getClientOriginalName();

// duplicate file check
if (file_exists(public_path('uploads/' . $fileName))) {
    return response()->json([
        'message' => 'This file is already uploaded'
    ], 400);
}

        Excel::import(new UsersImport, $filePath);

        return response()->json([
            'message' => 'File uploaded and data inserted successfully'
        ]);
    }
}