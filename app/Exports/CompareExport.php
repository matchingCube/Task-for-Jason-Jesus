<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class CompareExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    private $search;
    private $compare_list;
    private $sheet_name;


    public function __construct($search, $compare_list, $sheet_name = 'Compare')
    {
        $this->search = $search;
        $this->compare_list = $compare_list;
        $this->sheet_name = $sheet_name;
    }

    public function view(): View
    {
        $excel_data = get_data_compare($this->search, $this->compare_list);
        return view('util.download_data.compare', [
            'headers'       => $excel_data->header,
            'values'        => $excel_data->data,
            'compare_list'  => $this->compare_list
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:A10000')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('B2:D10000')->getAlignment()->setHorizontal('right');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheet_name;
    }
}