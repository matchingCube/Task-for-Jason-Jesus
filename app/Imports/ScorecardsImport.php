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

use App\Models\Persons;
use App\Models\Scorecards;

class ScorecardsImport implements ToArray, WithEvents
{
    public $sheetNames;
    //public $sheetData;

    public function __construct()
    {
        $this->sheetNames = [];
        //$this->sheetData = [];
    }

    public function array(array $array)
    {
        //$this->sheetData[$this->sheetNames[count($this->sheetNames)-1]] = $array;

        $name = $this->sheetNames[count($this->sheetNames)-1];
        $fedex_id = $array[0][1];
        $drug_test = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[1][1])->format('Y-m-d');
        $birth = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[2][1])->format('Y-m-d');
        $mec = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[3][1])->format('Y-m-d');
        $mvr = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[4][1])->format('Y-m-d');
        $cov = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[5][1])->format('Y-m-d');
        $email = $array[6][1];
        
        $persons = Persons::select([
                                    'id'
                                ])
                        ->where([
                                    'fedex_id'  => $fedex_id,
                                    'name'      => $name,
                                    'birth'     => $birth
                                ])
                        ->get();
        if (count($persons) >= 1) {
            $person_id = $persons[0]->id;
            Persons::where([
                            'id'  => $person_id
                        ])
                    ->delete();
            Scorecards::where([
                            'person_id'  => $person_id
                        ])
                    ->delete();
        }
        $person_id = Persons::insertGetId([
                                            'name'          => $name,
                                            'fedex_id'      => $fedex_id,
                                            'birth'         => $birth,
                                            'drug_test'     => $drug_test,
                                            'mec'           => $mec,
                                            'mvr'           => $mvr,
                                            'cov'           => $cov,
                                            'email'         => $email,
                                            'company_id'    => Auth::user()->company_id,
                            ]);
        
        $start_offset = 9;
        $end_offset = 18;
        for ($i = $start_offset; $i <= $end_offset; $i++) {
            $type = $array[$i][0];
            $wk_47 = $array[$i][47];
            $wk_48 = $array[$i][48];
            $wk_49 = $array[$i][49];
            $wk_50 = $array[$i][50];
            $wk_51 = $array[$i][51];
            $wk_52 = $array[$i][52];

            $q1 = $array[$i][54];
            $q2 = $array[$i][55];
            $q3 = $array[$i][56];
            $q4 = $array[$i][57];
            Scorecards::insert([
                                    'person_id'     => $person_id,
                                    'type'          => $type,
                                    'wk_47'         => $wk_47,
                                    'wk_48'         => $wk_48,
                                    'wk_49'         => $wk_49,
                                    'wk_50'         => $wk_50,
                                    'wk_51'         => $wk_51,
                                    'wk_52'         => $wk_52,
                                    'q1'            => $q1,
                                    'q2'            => $q2,
                                    'q3'            => $q3,
                                    'q4'            => $q4,
                                    'company_id'    => Auth::user()->company_id,
                                ]);
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