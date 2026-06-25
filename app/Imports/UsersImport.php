<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Each row of Excel will come here
     */
    public function model(array $row)
    {
        // 1. empty row skip
        if (empty($row['name']) || empty($row['email'])) {
            return null;
        }

        // 2. duplicate email check (IMPORTANT 🔥)
        $exists = Student::where('email', $row['email'])->first();

        if ($exists) {
            return null; // skip duplicate row
        }

        // 3. insert into database
        return new Student([
            'name'  => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'] ?? null,
        ]);
    }
}