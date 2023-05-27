<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $convertedData = [];

        foreach ($this->data as $item) {
            $convertedData[] = [$item];
        }

        return $convertedData;
    }

    public function headings(): array
    {
        return [
            'ID',
        ];
    }
}
