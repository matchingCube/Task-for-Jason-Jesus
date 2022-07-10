<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class TripsDriverExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    private $search;
    private $sheet_name;

    public function __construct($search, $sheet_name = 'Trips by driver')
    {
        $this->search = $search;
        $this->sheet_name = $sheet_name;
    }
    
    public function view(): View
    {
        $excel_data = get_data_trips_driver($this->search);
        return view('util.download_data.trips_driver', [
            'headers'   => $excel_data->header,
            'values'    => $excel_data->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B2:Z1000')->getAlignment()->setHorizontal('right');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheet_name;
    }
}