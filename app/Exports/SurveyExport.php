<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class SurveyExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [
            ['name' => 'John', 'email' => 'john@example.com', 'age' => 30],
            ['name' => 'Jane', 'email' => 'jane@example.com', 'age' => 25],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'age' => 35],
        ];
        return collect($data);
    }

    public function headings(): array
    {
        return ["name", "email", "age"];
    }
}
