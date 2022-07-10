<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\WeeklySchedule;
use App\Models\Linehaul_Drivers;

use Illuminate\Support\Facades\Auth;

class ScheduleImport implements ToArray, WithEvents
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
        $fleet_net = (string)$array[0][1];

        $start = 5;
        for ($i = $start; $i < count($array); $i++) {
            $year               = $array[$i][0];
            $week               = $array[$i][1];
            
            // $from = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][2])->format('Y-m-d');
            // $to = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][3])->format('Y-m-d');
            $from               = (string)$array[$i][2];
            $to                 = (string)$array[$i][3];
            $driver_name        = (string)$array[$i][4];
            $driver_id          = (string)$array[$i][5];
            $driver_phone       = (string)$array[$i][6];
            $tcheck             = (string)$array[$i][7];
            $spare_unit         = (string)$array[$i][8];

            $sat_tractor_id     = (string)$array[$i][9];
            $sat_start_time     = (string)$array[$i][10];
            $sun_tractor_id     = (string)$array[$i][11];
            $sun_start_time     = (string)$array[$i][12];
            $mon_tractor_id     = (string)$array[$i][13];
            $mon_start_time     = (string)$array[$i][14];
            $tue_tractor_id     = (string)$array[$i][15];
            $tue_start_time     = (string)$array[$i][16];
            $wed_tractor_id     = (string)$array[$i][17];
            $wed_start_time     = (string)$array[$i][18];
            $thu_tractor_id     = (string)$array[$i][19];
            $thu_start_time     = (string)$array[$i][20];
            $fri_tractor_id     = (string)$array[$i][21];
            $fri_start_time     = (string)$array[$i][22];

            $schedules = WeeklySchedule::where('year_num', $year)
                                        ->where('week_num', $week)
                                        ->where('driver_id', $driver_id)
                                        ->get()
                                        ->all();

            // save data into weekly_schedule table
            if (empty($schedules)) {
                // insert new data from csv/xlsx into database
                WeeklySchedule::insert([
                    'year_num'          => $year,
                    'week_num'          => $week,
                    'from_date'         => $from,
                    'to_date'           => $to,
                    'driver_id'         => $driver_id,
                    'driver_name'       => $driver_name,
                    'driver_phone'      => $driver_phone,
                    'tcheck'            => $tcheck,
                    'spare_unit'        => $spare_unit,
                    'fleet_net'         => $fleet_net,
                    'sat_tractor_id'    => $sat_tractor_id,
                    'sat_start_time'    => $sat_start_time,
                    'sun_tractor_id'    => $sun_tractor_id,
                    'sun_start_time'    => $sun_start_time,
                    'mon_tractor_id'    => $mon_tractor_id,
                    'mon_start_time'    => $mon_start_time,
                    'tue_tractor_id'    => $tue_tractor_id,
                    'tue_start_time'    => $tue_start_time,
                    'wed_tractor_id'    => $wed_tractor_id,
                    'wed_start_time'    => $wed_start_time,
                    'thu_tractor_id'    => $thu_tractor_id,
                    'thu_start_time'    => $thu_start_time,
                    'fri_tractor_id'    => $fri_tractor_id,
                    'fri_start_time'    => $fri_start_time,
                    'company_id'        => Auth::user()->company_id,
                ]);
            } else {
                // update data
                $s = $schedules[0];

                $s->from_date       = $from;
                $s->to_date         = $to;
                $s->driver_name     = $driver_name;
                $s->driver_phone    = $driver_phone;
                $s->tcheck          = $tcheck;
                $s->spare_unit      = $spare_unit;
                $s->fleet_net       = $fleet_net;
                $s->sat_tractor_id  = $sat_tractor_id;
                $s->sat_start_time  = $sat_start_time;
                $s->sun_tractor_id  = $sun_tractor_id;
                $s->sun_start_time  = $sun_start_time;
                $s->mon_tractor_id  = $mon_tractor_id;
                $s->mon_start_time  = $mon_start_time;
                $s->tue_tractor_id  = $tue_tractor_id;
                $s->tue_start_time  = $tue_start_time;
                $s->wed_tractor_id  = $wed_tractor_id;
                $s->wed_start_time  = $wed_start_time;
                $s->thu_tractor_id  = $thu_tractor_id;
                $s->thu_start_time  = $thu_start_time;
                $s->fri_tractor_id  = $fri_tractor_id;
                $s->fri_start_time  = $fri_start_time;

                $s->save();
            }

            // save data into linehaul_drivers table
            $drivers = Linehaul_Drivers::where('driver_id', $driver_id)->get()->all();

            if (empty($drivers)) {
                // insert driver info
                Linehaul_Drivers::insert([
                    'driver_id'     => $driver_id,
                    'driver_name'   => $driver_name,
                    'phone'         => $driver_phone,
                    'company_id'    => Auth::user()->company_id,
                ]);
            } else {
                // update driver info
                $driver = $drivers[0];

                if ($driver->driver_name != $driver_name || $driver->phone != $driver_phone) {
                    $driver->driver_name    = $driver_name;
                    $driver->phone          = empty($driver_phone) ? $driver->phone : $driver_phone;

                    $driver->save();
                }
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