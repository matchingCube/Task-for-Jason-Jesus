<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\Fleet;

class FleetsImport implements ToArray, WithEvents
{
    public $sheetNames;
    
    public function __construct()
    {
        $this->sheetNames = [];
    }

    public function array(array $array)
    {
        $name = $this->sheetNames[count($this->sheetNames)-1];
        
        $start = 1;
        for ($i = $start; $i < count($array); $i++) {
            $service_provider   = (string)$array[$i][0];
            $tractor_id         = (string)$array[$i][1];
            $model              = (string)$array[$i][2];
            $vin                = (string)$array[$i][3];
            $year               = $array[$i][4];
            $license_plate      = (string)$array[$i][5];
            $book_value         = $array[$i][6] ? $array[$i][6] : 0;
            $t_check            = (string)$array[$i][7];
            $pre_pass           = (string)$array[$i][8];
            $vedr               = (string)$array[$i][9];
            $eld                = (string)$array[$i][10];
            $qiv                = (string)$array[$i][11];
            $bit                = $array[$i][12] ? date('Y-m-d', strtotime((string)$array[$i][12])) : null;
            $domicile           = (string)$array[$i][13];
            $domicile_email     = (string)$array[$i][14];

            $fleets = Fleet::where('tractor_id', $tractor_id)
                            ->get()
                            ->all();

            // save data into fleets table
            if (empty($fleets)) {
                // insert new data from csv/xlsx into database
                Fleet::insert([
                    'service_provider'  => $service_provider,
                    'tractor_id'        => $tractor_id,
                    'model'             => $model,
                    'vin'               => $vin,
                    'year'              => $year,
                    'license_plate'     => $license_plate,
                    'book_value'        => $book_value,
                    't_check'           => $t_check,
                    'pre_pass'          => $pre_pass,
                    'vedr'              => $vedr,
                    'eld'               => $eld,
                    'qiv'               => $qiv,
                    'bit'               => $bit,
                    'domicile'          => $domicile,
                    'domicile_email'    => $domicile_email,
                    'company_id'        => Auth::user()->company_id,
                ]);
            } else {
                // update data
                $f = $fleets[0];

                $f->service_provider    = $service_provider;
                $f->tractor_id          = $tractor_id;
                $f->model               = $model;
                $f->vin                 = $vin;
                $f->year                = $year;
                $f->license_plate       = $license_plate;
                $f->book_value          = $book_value;
                $f->t_check             = $t_check;
                $f->pre_pass            = $pre_pass;
                $f->vedr                = $vedr;
                $f->eld                 = $eld;
                $f->qiv                 = $qiv;
                $f->bit                 = $bit;
                $f->domicile            = $domicile;
                $f->domicile_email      = $domicile_email;
                                
                $f->save();
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            },
        ];
    }
}