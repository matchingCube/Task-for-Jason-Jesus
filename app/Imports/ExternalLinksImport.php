<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\External_Links;

use Illuminate\Support\Facades\Auth;

class ExternalLinksImport implements ToArray, WithEvents
{
    public $sheetNames;

    public function __construct()
    {
        $this->sheetNames = [];
    }

    public function array(array $array)
    {
        for ($i = 1; $i < count($array); $i++) {
            $name = $array[$i][0];
            $url = str_replace(" ", "", $array[$i][1]);
            $description = $array[$i][2];
            External_Links::insert([
                                        'name'          => $name,
                                        'url'           => $url,
                                        'description'   => $description,
                                        'company_id'    => Auth::user()->company_id
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