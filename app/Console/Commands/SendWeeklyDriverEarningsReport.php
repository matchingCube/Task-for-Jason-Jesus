<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Models\Company;
use App\Models\GlobalSetting;
use App\Models\Linehaul_Trips;
use App\Models\FixedRateSetting;
use App\Models\Linehaul_Drivers;

use App\Mail\DriverEarningsReportMail;

use App\Scopes\CompanyScope;

use Carbon\Carbon;
use stdClass;

class SendWeeklyDriverEarningsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly_driver_earnings_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails for weekly earnings report to drivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get sending email methods regarding to all companies
        $sending_email_methods = GlobalSetting::withoutGlobalScope(CompanyScope::class)
                                        ->where('module', 'payroll')
                                        ->where('key', 'sending_method')
                                        ->get()
                                        ->all();
        
        foreach ($sending_email_methods as $sem) {
            if ($sem->value == '0') {
                continue;
            }

            $company_id = $sem->company_id;
            
            $company = Company::find($company_id);

            $delivery_date = GlobalSetting::withoutGlobalScope(CompanyScope::class)
                                        ->where('company_id', $company_id)
                                        ->where('module', 'payroll')
                                        ->where('key', 'delivery_date')
                                        ->get()
                                        ->first();
            
            // skip to send email if today is not delivery date
            if (!$delivery_date ||
                ($delivery_date && $delivery_date->value == null) ||
                ($delivery_date && $delivery_date->value != date("l"))) {
                continue;
            }

            // get from date, to date, payment date from payment date setting
            $payment_date = GlobalSetting::withoutGlobalScope(CompanyScope::class)
                                        ->where('company_id', $company_id)
                                        ->where('module', 'payroll')
                                        ->where('key', 'payment_date')
                                        ->get()
                                        ->first();
            if (empty($payment_date) || empty($payment_date->value)) {
                $payment_date = get_day_of_week_from_string();
            } else {
                $payment_date = get_day_of_week_from_string($payment_date->value);
            }

            $from_date = get_from_date($payment_date, 'Y-m-d');
            $to_date = get_to_date($payment_date, 'Y-m-d');

            // get fixed rates and drivers
            $fixed_rates = FixedRateSetting::withoutGlobalScope(CompanyScope::class)
                                        ->where('company_id', $company_id)
                                        ->get()
                                        ->all();
            $drivers = Linehaul_Drivers::withoutGlobalScope(CompanyScope::class)
                                    ->where('company_id', $company_id)
                                    ->where('work_status', 1)
                                    ->get()
                                    ->all();
            
            foreach ($drivers as $d) {
                $payroll = new \stdClass();
                $payroll->id = $d->id;
                $payroll->driver_id = $d->driver_id;
                $payroll->driver_name = $d->driver_name;
                $payroll->work_status = $d->work_status;
                $payroll->price_per_mile = $d->price_per_mile;
                $payroll->total_miles = 0;
                $payroll->fr_miles = 0;
                $payroll->other_miles = 0;
                $payroll->total_price = 0;
                $payroll->fr_price = 0;
                $payroll->other_price = 0;
    
                $trips = Linehaul_Trips::withoutGlobalScope(CompanyScope::class)
                                    ->where('company_id', $company_id)
                                    ->where('date', '>=', $from_date)
                                    ->where('date', '<=', $to_date)
                                    ->where('driver_1', $d->driver_id)
                                    ->get()
                                    ->all();
                
                $new_trips = [];
                $fr_trips_num = 0;
                foreach ($trips as $t) {
                    $new_trip = new stdClass();
                    $new_trip->date = Carbon::createFromFormat('Y-m-d', $t->date)->format('m/d/Y');
                    $new_trip->origin = $t->leg_org;
                    $new_trip->destination = $t->leg_dest;
                    $new_trip->miles = $t->miles_qty;
                    $new_trip->value = 0;
                    $new_trip->pay_rate = 0;
                    $new_trip->pay_rate_unit = '';
                    
                    $flag = false;
                    foreach ($fixed_rates as $r) {
                        if ($t->miles_qty >= $r->from_miles && $t->miles_qty <= $r->to_miles) {
                            $payroll->fr_miles += $t->miles_qty;
                            $payroll->fr_price += $r->fixed_rate;

                            $new_trip->value = $r->fixed_rate;

                            $new_trip->pay_rate = $r->fixed_rate;
                            $new_trip->pay_rate_unit = '$';

                            $fr_trips_num++;

                            $flag = true;
                            break;
                        }
                    }
    
                    // in case miles is not in mileage for fixed rate
                    if (!$flag) {
                        $payroll->other_miles += $t->miles_qty;

                        $new_trip->value = $t->miles_qty * $d->price_per_mile;
                        
                        $new_trip->pay_rate = $d->price_per_mile;
                        $new_trip->pay_rate_unit = '';
                    }

                    $new_trips[] = $new_trip;
                }
    
                $payroll->other_price = $payroll->other_miles * $d->price_per_mile;
                $payroll->total_miles = $payroll->fr_miles + $payroll->other_miles;
                $payroll->total_price = $payroll->fr_price + $payroll->other_price;
                $payroll->trips = $new_trips;
                $payroll->fr_trips_num = $fr_trips_num;
                $payroll->trips_num = count($new_trips);
                
                $payroll->company = $company;
                
                $payroll->from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('m/d/Y');
                $payroll->to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('m/d/Y');
                $payroll->payment_date = Carbon::createFromFormat('Y-m-d', get_payment_date($payment_date, 'Y-m-d'))->format('m/d/Y');
                                
                if ($d->email) {
                    Mail::to($d->email)->send(new DriverEarningsReportMail($payroll));
                }
            }
        }

        return 0;
    }
}
