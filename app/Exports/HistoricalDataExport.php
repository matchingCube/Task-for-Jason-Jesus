<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HistoricalDataExport implements WithMultipleSheets
{
    use Exportable;

    protected $search;
    
    public function __construct($search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $compare_list = array();
        $sheet_names = array();
        foreach ($this->search->key_metrics as $key_metric) {
            if ($key_metric == 'revenue' || $key_metric == 'miles-total' || $key_metric == 'fuelcost-total') {
                array_push($compare_list, $key_metric);
            } else {
                array_push($sheet_names, $key_metric);
            }
        }
        if (!empty($compare_list)) {
            array_unshift($sheet_names, 'compare');
        }

        foreach ($sheet_names as $sheet_name) {
            switch ($sheet_name) {
                case 'compare':
                    $sheets[] = new CompareExport($this->search, $compare_list, 'Compare');
                    break;
                case 'miles-driver':
                    $sheets[] = new MileDriverExport($this->search, 'Miles by driver');
                    break;
                case 'miles-vehicle':
                    $sheets[] = new MileVehicleExport($this->search, 'Miles by vehicle');
                    break;
                case 'trips-driver':
                    $sheets[] = new TripsDriverExport($this->search, 'Trips by driver');
                    break;
                case 'mpg-vehicle':
                    $sheets[] = new MpgVehicleExport($this->search, 'MPG by vehicle');
                    break;
            }
        }

        return $sheets;
    }
}