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

use App\Models\Linehaul_Drivers;

class DriversImport implements ToArray, WithEvents
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
            $driver_id          = (string)$array[$i][0];
            $driver_name        = (string)$array[$i][1];
            $email              = (string)$array[$i][2];
            $phone              = (string)$array[$i][3];
            $license            = (string)$array[$i][4];
            $address            = (string)$array[$i][5];
            $price_per_mile     = $array[$i][6];
            $work_status        = $array[$i][7];

            $drivers = Linehaul_Drivers::where('driver_id', $driver_id)
                                        ->get()
                                        ->all();

            // save data into linehaul_drivers table
            if (empty($drivers)) {
                // insert new data from csv/xlsx into database
                Linehaul_Drivers::insert([
                    'driver_id'         => $driver_id,
                    'driver_name'       => $driver_name,
                    'email'             => $email,
                    'phone'             => $phone,
                    'license'           => $license,
                    'address'           => $address,
                    'price_per_mile'    => $price_per_mile,
                    'work_status'       => $work_status,
                    'company_id'        => Auth::user()->company_id,
                ]);
            } else {
                // update data
                $d = $drivers[0];

                $d->driver_id       = $driver_id;
                $d->driver_name     = $driver_name;
                $d->email           = $email;
                $d->phone           = $phone;
                $d->license         = $license;
                $d->address         = $address;
                $d->price_per_mile  = $price_per_mile;
                $d->work_status     = $work_status;
                
                $d->save();
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