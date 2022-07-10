<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\support\Facades\Redirect;
use illuminate\support\Facades\Auth;

use App\Models\WeeklySchedule;
use App\Models\Linehaul_Drivers;

use App\Imports\ScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Rest\Client;

class WeeklyScheduleController extends Controller
{
    public function index()
    {
        $this->authorize('manage-schedule');

        return view('schedule.upload');
    }

    public function upload(Request $request)
    {
        $this->authorize('manage-schedule');

        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new ScheduleImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'Weekly Schedules were imported from <b>' . $file_name . '</b> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return view('schedule.upload');
    }

    public function search(Request $request)
    {
        $this->authorize('manage-schedule');

        $year_num = $request->input('year-num') ?? date("Y");
        $week_num = $request->input('week-num') ?? date("W");
        $driver_id = $request->input('driver-id') ?? '';

        $schedules = WeeklySchedule::with('driver')
                                ->where('year_num', $year_num)
                                ->where('week_num', $week_num)
                                ->where('driver_id', 'like', '%' . $driver_id . '%')
                                ->get()
                                ->all();
        
        return view('schedule.search', compact('year_num', 'week_num', 'driver_id', 'schedules'));
    }

    public function getSchedule(Request $request)
    {
        $this->authorize('manage-schedule');

        $schedule_id = $request->s_id;
        if (empty($schedule_id)) {
            $schedule = null;
            $weekly = null;
        } else {
            $schedule = WeeklySchedule::find($schedule_id);

            if (empty($schedule)) {
                $schedule = null;
                $weekly = null;
            } else {
                $weekly = array();
                
                $d = new \stdClass();
                $d->day = "Saturday";
                $d->tractor_id = $schedule->sat_tractor_id;
                $d->start_time = $schedule->sat_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Sunday";
                $d->tractor_id = $schedule->sun_tractor_id;
                $d->start_time = $schedule->sun_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Monday";
                $d->tractor_id = $schedule->mon_tractor_id;
                $d->start_time = $schedule->mon_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Tuesday";
                $d->tractor_id = $schedule->tue_tractor_id;
                $d->start_time = $schedule->tue_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Wednesday";
                $d->tractor_id = $schedule->wed_tractor_id;
                $d->start_time = $schedule->wed_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Thursday";
                $d->tractor_id = $schedule->thu_tractor_id;
                $d->start_time = $schedule->thu_start_time;
                array_push($weekly, $d);
    
                $d = new \stdClass();
                $d->day = "Friday";
                $d->tractor_id = $schedule->fri_tractor_id;
                $d->start_time = $schedule->fri_start_time;
                array_push($weekly, $d);
            }
        }
        
        return view('schedule.view', compact('schedule', 'weekly'));
    }

    public function getSchedulePost(Request $request)
    {
        $this->authorize('manage-schedule');

        $schedule_id = $request->input('id');
        $schedule = WeeklySchedule::find($schedule_id);
        if (!$schedule) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the schedule info by id = {$schedule_id}"
            ]);
        } else {
            return response()->json([
                'type' => 'success',
                'schedule' => $schedule
            ]);
        }
    }

    public function editSchedule(Request $request)
    {
        $this->authorize('manage-schedule');

        $id = $request->route()->parameter('id');
        $schedule = WeeklySchedule::find($id);
        if (!$schedule) {
            $drivers = null;
            $request->session()->flash('error', "Can't find the schedule info by id = {$id}");
        } else {
            $drivers = Linehaul_Drivers::where('work_status', 1)->get()->all();
        }
        
        return view('schedule.edit', compact('schedule', 'drivers'));
    }

    public function addSchedule()
    {
        $this->authorize('manage-schedule');

        $drivers = Linehaul_Drivers::where('work_status', 1)->get()->all();
        
        return view('schedule.edit', compact('drivers'));
    }

    public function saveSchedule(Request $request)
    {
        $this->authorize('manage-schedule');

        $id = $request->input('id');

        $year_num       = $request->input('year_num');
        $week_num       = $request->input('week_num');
        $from_date      = $request->input('from_date');
        $to_date        = $request->input('to_date');
        $driver_id      = $request->input('driver_id');
        $sent_sms       = $request->input('sent_sms');
        $response       = $request->input('response');
        $driver_phone   = $request->input('driver_phone');
        $tcheck         = $request->input('tcheck');
        $spare_unit     = $request->input('spare_unit');
        $fleet_net      = $request->input('fleet_net');
        
        $sat_start_time = $request->input('sat_start_time');
        $sat_tractor_id = $request->input('sat_tractor_id');
        $sun_start_time = $request->input('sun_start_time');
        $sun_tractor_id = $request->input('sun_tractor_id');
        $mon_start_time = $request->input('mon_start_time');
        $mon_tractor_id = $request->input('mon_tractor_id');
        $tue_start_time = $request->input('tue_start_time');
        $tue_tractor_id = $request->input('tue_tractor_id');
        $wed_start_time = $request->input('wed_start_time');
        $wed_tractor_id = $request->input('wed_tractor_id');
        $thu_start_time = $request->input('thu_start_time');
        $thu_tractor_id = $request->input('thu_tractor_id');
        $fri_start_time = $request->input('fri_start_time');
        $fri_tractor_id = $request->input('fri_tractor_id');
        
        $driver = Linehaul_Drivers::find($driver_id);
        $drivers = Linehaul_Drivers::where('work_status', 1)->get()->all();

        if (!$driver) {
            $request->session()->flash('error', "Can't find the driver info by #{$driver_id}. Please retry after added new driver.");
            return Redirect::back()->withInput();
        } else {
            if ($id) {
                //update
                $schedule = WeeklySchedule::find($id);
                if (!$schedule) {
                    $request->session()->flash('error', "Can't find the schedule info by #{$id} for updating.");
                    return Redirect::back()->withInput();
                } else {
                    $existed = WeeklySchedule::where('year_num', $year_num)
                                            ->where('week_num', $week_num)
                                            ->where('driver_id', $driver->driver_id)
                                            ->get()
                                            ->all();
                    if ($existed && $existed[0]->id != $schedule->id) {
                        $request->session()->flash('error', "Exists already the schedule with the same year, week, driver id.");
                        return Redirect::back()->withInput();
                    } else {
                        $schedule->year_num         = $year_num;
                        $schedule->week_num         = $week_num;
                        $schedule->from_date        = $from_date;
                        $schedule->to_date          = $to_date;
                        $schedule->driver_id        = $driver->driver_id;
                        $schedule->driver_name      = $driver->driver_name;
                        $schedule->driver_phone     = $driver_phone;
                        $schedule->tcheck           = $tcheck;
                        $schedule->spare_unit       = $spare_unit;
                        $schedule->fleet_net        = $fleet_net;
                        $schedule->sent_sms         = $sent_sms;
                        $schedule->response         = $response;
                        $schedule->sat_start_time   = $sat_start_time;
                        $schedule->sat_tractor_id   = $sat_tractor_id;
                        $schedule->sun_start_time   = $sun_start_time;
                        $schedule->sun_tractor_id   = $sun_tractor_id;
                        $schedule->mon_start_time   = $mon_start_time;
                        $schedule->mon_tractor_id   = $mon_tractor_id;
                        $schedule->tue_start_time   = $tue_start_time;
                        $schedule->tue_tractor_id   = $tue_tractor_id;
                        $schedule->wed_start_time   = $wed_start_time;
                        $schedule->wed_tractor_id   = $wed_tractor_id;
                        $schedule->thu_start_time   = $thu_start_time;
                        $schedule->thu_tractor_id   = $thu_tractor_id;
                        $schedule->fri_start_time   = $fri_start_time;
                        $schedule->fri_tractor_id   = $fri_tractor_id;

                        $schedule->save();

                        $schedules = WeeklySchedule::with('driver')
                                                    ->where('year_num', $year_num)
                                                    ->where('week_num', $week_num)
                                                    ->get()
                                                    ->all();

                        $request->session()->flash('success', "Schedule({$schedule->id}) was updated successfully!");
                        return view('schedule.search', compact('year_num', 'week_num', 'schedules'));
                    }
                }
            } else {
                $existed = WeeklySchedule::where('year_num', $year_num)
                                        ->where('week_num', $week_num)
                                        ->where('driver_id', $driver->driver_id)
                                        ->get()
                                        ->all();
                if ($existed) {
                    $request->session()->flash('error', "Exists already the schedule with the same year, week, driver id.");
                    return Redirect::back()->withInput();
                }
                
                $schedule = new WeeklySchedule();
                $schedule->year_num         = $year_num;
                $schedule->week_num         = $week_num;
                $schedule->from_date        = $from_date;
                $schedule->to_date          = $to_date;
                $schedule->driver_id        = $driver->driver_id;
                $schedule->driver_name      = $driver->driver_name;
                $schedule->driver_phone     = $driver_phone;
                $schedule->tcheck           = $tcheck;
                $schedule->spare_unit       = $spare_unit;
                $schedule->fleet_net        = $fleet_net;
                $schedule->sent_sms         = $sent_sms;
                $schedule->response         = $response;
                $schedule->sat_start_time   = $sat_start_time;
                $schedule->sat_tractor_id   = $sat_tractor_id;
                $schedule->sun_start_time   = $sun_start_time;
                $schedule->sun_tractor_id   = $sun_tractor_id;
                $schedule->mon_start_time   = $mon_start_time;
                $schedule->mon_tractor_id   = $mon_tractor_id;
                $schedule->tue_start_time   = $tue_start_time;
                $schedule->tue_tractor_id   = $tue_tractor_id;
                $schedule->wed_start_time   = $wed_start_time;
                $schedule->wed_tractor_id   = $wed_tractor_id;
                $schedule->thu_start_time   = $thu_start_time;
                $schedule->thu_tractor_id   = $thu_tractor_id;
                $schedule->fri_start_time   = $fri_start_time;
                $schedule->fri_tractor_id   = $fri_tractor_id;
                $schedule->company_id       = Auth::user()->company_id;

                $schedule->save();

                $schedules = WeeklySchedule::with('driver')
                                            ->where('year_num', $year_num)
                                            ->where('week_num', $week_num)
                                            ->get()
                                            ->all();

                $request->session()->flash('success', "Schedule({$schedule->id}) was added successfully!");
                return view('schedule.search', compact('year_num', 'week_num', 'schedules'));
            }
        }
    }

    public function removeSchedule(Request $request)
    {
        $this->authorize('manage-schedule');

        $id = $request->route()->parameter('id');

        $res = WeeklySchedule::find($id)->delete();

        if ($res) {
            $request->session()->flash('success', 'Schedule removed successfully. (Id: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this schedule at this time. (Id: ' . $id . ') Please retry later.');
        }
        return redirect('/schedule/search');
    }

    public function removeBulkSchedules(Request $request)
    {
        $this->authorize('manage-schedule');

        $ids = $request->input('ids');
        $ids = explode('|', $ids, -1);
        
        $res = WeeklySchedule::destroy($ids);
                
        if ($res) {
            return response()->json([
                'type' => 'success',
                'ids' => implode(', ', $ids)
            ]);
        } else {
            return response()->json([
                'type' => 'success',
                'message' => 'Sorry, we got the error while processing. Please retry later!'
            ]);
        }
    }

    public function sendSMS(Request $request)
    {
        $this->authorize('manage-schedule');

        $ids = $request->input('ids');
        $ids = explode('|', $ids, -1);
        
        $schedules = WeeklySchedule::whereIn('id', $ids)->get()->all();
        
        // send sms
        $twilio_auth_token = env('TWILIO_AUTH_TOKEN');
        $twilio_account_sid = env('TWILIO_ACCOUNT_SID');
        $twilio_number = env('TWILIO_NUMBER');

        $client = new Client($twilio_account_sid, $twilio_auth_token);

        $drivers = [];

        foreach($schedules as $s) {
            if (!empty(preg_replace('/\s+/', '', $s->driver_phone))) {
                $to = '+' . preg_replace('/\s+/', '', $s->driver_phone);
                array_push($drivers, $s->driver_name);

                $sms = "Sent from Ground Force Trucking\n";
                $sms .= "Your Schedule:\n";
                $sms .= "Week: " . $s->week_num . "\n";
                $sms .= "Dates: " . $s->from_date . "-" . $s->to_date . "\n";
                $sms .= "Saturday: " . $s->sat_start_time . ", " . $s->sat_tractor_id . "\n";
                $sms .= "Sunday: " . $s->sun_start_time . ", " . $s->sun_tractor_id . "\n";
                $sms .= "Monday: " . $s->mon_start_time . ", " . $s->mon_tractor_id . "\n";
                $sms .= "Tuesday: " . $s->tue_start_time . ", " . $s->tue_tractor_id . "\n";
                $sms .= "Wednesday: " . $s->wed_start_time . ", " . $s->wed_tractor_id . "\n";
                $sms .= "Thursday: " . $s->thu_start_time . ", " . $s->thu_tractor_id . "\n";
                $sms .= "Friday: " . $s->fri_start_time . ", " . $s->fri_tractor_id . "\n";
                $sms .= "------\n";
                $sms .= "Reply '1' to accept or '2' to reject schedule.";

                $client->messages->create(
                    $to,
                    array(
                        'from' => $twilio_number,
                        'body' => $sms,
                        // "mediaUrl" => ["http://dashboard.groundforcetrucking.com/sms-logo.png"]
                    )
                );

                // update sms_sent
                $s->sent_sms = 1;
                $s->save();
            }
        }

        return response()->json([
            'type' => 'success',
            'ids' => implode(', ', $drivers)
        ]);
    }

    public function handleIncomingSMS(Request $request)
    {
        $from = str_replace('+', '', $request->input('From'));
        $body = $request->input('Body');
        $response = (strpos($request->input('Body'), '1') === FALSE) ? ((strpos($request->input('Body'), '2') === FALSE) ? 0 : 2) : 1;
        
        $s = WeeklySchedule::whereRaw(
                                    "driver_phone = ? AND CONCAT(year_num, week_num) = (SELECT MAX(CONCAT(year_num, week_num)) FROM weekly_schedule WHERE driver_phone = ?)",
                                    [$from, $from])
                                ->get();

        if ($s) {
            $s[0]->response = $response;
            $s[0]->save();
        }
    }
}
