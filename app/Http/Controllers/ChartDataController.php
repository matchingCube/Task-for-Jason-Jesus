<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChartDataController extends Controller
{
    //last weeks to show on charts
    protected $limit = 6;
        
    public function total_miles_week(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                SUM(miles_qty) AS total_miles
                            FROM 
                                linehaul_trips
                            WHERE
                                year_num = {$year_num} AND
                                week_num <= {$week_num} AND
                                week_num > {$week_num} - {$limit} AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC
                            LIMIT {$limit}");
        $categories = array();
        for ($i = $week_num - $limit + 1; $i <= $week_num; $i++) {
            array_push($categories, 'WK-' . $i . ', ' . $year_num);
        }
        $values = array();
        foreach ($categories as $cat) {
            $value = 0;
            foreach ($weeks as $week) {
                if ($cat == 'WK-' . $week->week_num . ', ' . $week->year_num) {
                    $value = $week->total_miles;
                    break;
                };
            }
            $values[] = $value;
        }
        
        return view('chart.total_miles_week', [
            'year_num'      => $year_num,
            'week_num'      => $week_num,
            'categories'    => json_encode($categories),
            'values'        => json_encode($values),
        ]);
    }

    public function miles_week_driver(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $weeks = DB::select("
                            SELECT 
                                t.year_num,
                                t.week_num,
                                t.driver_1 AS driver_id,
                                d.driver_name,
                                SUM(t.miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            LEFT JOIN linehaul_drivers AS d ON d.driver_id = t.driver_1 AND d.company_id = {$company_id}
                            WHERE
                                t.year_num = {$year_num} AND
                                t.week_num <= {$week_num} AND
                                t.week_num > {$week_num} - {$limit} AND
                                t.company_id = {$company_id}
                            GROUP BY t.year_num, t.week_num, t.driver_1
                            ORDER BY t.year_num ASC, t.week_num ASC");
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;
        }
        return view('chart.miles_week_driver', [
            'year_num'  => $year_num,
            'week_num'  => $week_num,
            'data'      => json_encode($weeks),
            'limit'     => $limit
        ]);
    }

    public function miles_week_vehicle(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                vehicle,
                                SUM(miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            WHERE
                                year_num = {$year_num} AND
                                week_num <= {$week_num} AND
                                week_num > {$week_num} - {$limit} AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num, vehicle
                            ORDER BY year_num ASC, week_num ASC");
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;
        }
        return view('chart.miles_week_vehicle', [
            'data'      => json_encode($weeks),
            'year_num'  => $year_num,
            'week_num'  => $week_num,
            'limit'     => $limit
        ]);
    }

    public function mpg_week_vehicle(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $week_miles = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    vehicle,
                                    SUM(miles_qty) AS miles
                                FROM 
                                    linehaul_trips AS t
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        foreach ($week_miles as $week_mile) {
            $week_mile->week = 'WK-' . $week_mile->week_num . ', ' . $week_mile->year_num;
        }

        $week_fuels = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    vehicle,
                                    SUM(qty) AS fuel_qty
                                FROM 
                                    fuel_purchases
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        foreach ($week_fuels as $week_fuel) {
            $week_fuel->week = 'WK-' . $week_fuel->week_num . ', ' . $week_fuel->year_num;
        }
        return view('chart.mpg_week_vehicle', [
            'miles_data'    => json_encode($week_miles),
            'fuels_data'    => json_encode($week_fuels),
            'year_num'      => $year_num,
            'week_num'      => $week_num,
            'limit'         => $limit
        ]);
    }

    public function total_fuelcost_week(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                SUM(pur_amt) AS cost
                            FROM 
                                fuel_purchases
                            WHERE
                                year_num = {$year_num} AND
                                week_num <= {$week_num} AND
                                week_num > {$week_num} - {$limit} AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");

        $categories = array();
        for ($i = $week_num - $limit + 1; $i <= $week_num; $i++) {
            array_push($categories, 'WK-' . $i . ', ' . $year_num);
        }
        $values = array();
        foreach($categories as $cat) {
            $value = 0;
            foreach ($weeks as $week) {
                if ($cat == 'WK-' . $week->week_num . ', ' . $week->year_num) {
                    $value = round($week->cost, 2);
                }
            }
            $values[] = [$cat, $value];
        }
        
        return view('chart.total_fuelcost_week', [
            'data' => json_encode($values),
            'year_num'      => $year_num,
            'week_num'      => $week_num
        ]);
    }

    public function fuelcost_week_vehicle(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                vehicle,
                                SUM(pur_amt) AS cost
                            FROM 
                                fuel_purchases
                            WHERE
                                year_num = {$year_num} AND
                                week_num <= {$week_num} AND
                                week_num > {$week_num} - {$limit} AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num, vehicle
                            ORDER BY year_num ASC, week_num ASC");
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;
            $week->cost = round($week->cost, 2);
        }
        return view('chart.fuelcost_week_vehicle', [
            'data'      => json_encode($weeks),
            'year_num'  => $year_num,
            'week_num'  => $week_num,
            'limit'     => $limit    
        ]);
    }

    public function total_revenue_week(Request $request)
    {
        $this->authorize('manage-kpi');

        $company_id = Auth::user()->company_id;

        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W") - 1;
        }

        $limit = $this->limit;
        if ($week_num < $limit) {
            $limit = $week_num;
        }
               
        $grosses            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    SUM(daily_gross_amt) AS gross
                                FROM 
                                    linehaul_trips
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $o_s_adjustments    = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    SUM(amt) AS o_s_adjustments
                                FROM 
                                    other_settlement_adjustments
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $fuels              = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    -SUM(auth_chgbk_net) AS fuel_cost
                                FROM 
                                    fuel_purchases
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $repairs            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    -SUM(repair_misc_amt) AS repair_cost
                                FROM 
                                    tractor_repairs_misc
                                WHERE
                                    year_num = {$year_num} AND
                                    week_num <= {$week_num} AND
                                    week_num > {$week_num} - {$limit} AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $weeks = array();
        for ($i = $week_num - $limit + 1; $i <= $week_num; $i++) {
            array_push($weeks, 'WK-' . $i . ', ' . $year_num);
        }
        
        foreach ($grosses as $gross) {
            $gross->week = 'WK-' . $gross->week_num . ', ' . $gross->year_num;
            $gross->gross = round($gross->gross, 2);
        }
        foreach ($o_s_adjustments as $adjustment) {
            $adjustment->week = 'WK-' . $adjustment->week_num . ', ' . $adjustment->year_num;
            $adjustment->o_s_adjustments = round($adjustment->o_s_adjustments, 2);
        }
        foreach ($fuels as $fuel) {
            $fuel->week = 'WK-' . $fuel->week_num . ', ' . $fuel->year_num;
            $fuel->fuel_cost = round($fuel->fuel_cost, 2);
        }
        foreach ($repairs as $repair) {
            $repair->week = 'WK-' . $repair->week_num . ', ' . $repair->year_num;
            $repair->repair_cost = round($repair->repair_cost, 2);
        }
        
        $revenues = array();
        $ytd_revenues = array();
        foreach ($weeks as $week) {
            $revenue = 0;
            foreach ($grosses as $gross) {
                if ($gross->week == $week) {
                    $revenue += $gross->gross;
                    break;
                }
            }
            foreach ($o_s_adjustments as $adjustment) {
                if ($adjustment->week == $week) {
                    $revenue += $adjustment->o_s_adjustments;
                    break;
                }
            }
            foreach ($fuels as $fuel) {
                if ($fuel->week == $week) {
                    $revenue -= $fuel->fuel_cost;
                    break;
                }
            }
            foreach ($repairs as $repair) {
                if ($repair->week == $week) {
                    $revenue -= $repair->repair_cost;
                    break;
                }
            }

            array_push($revenues, round($revenue, 2));
            array_push($ytd_revenues, 0);
        }

        $ytd_revenue = DB::select("
                            SELECT 
                                {$year_num} as year_num,
                                (gross + o_s_adjustments - fuel_cost - repair_cost) AS revenue
                            FROM
                                (SELECT 
                                    (SELECT 
                                        IFNULL(SUM(t.daily_gross_amt), 0)
                                    FROM linehaul_trips AS t
                                    WHERE YEAR(t.date) = {$year_num} AND t.company_id = {$company_id}) AS gross,
                                    (SELECT 
                                        IFNULL(SUM(t.amt), 0)
                                    FROM other_settlement_adjustments AS t
                                    WHERE YEAR(t.date) = {$year_num} AND t.company_id = {$company_id}) AS o_s_adjustments,
                                    (SELECT 
                                        IFNULL(-SUM(t.auth_chgbk_net), 0)
                                    FROM fuel_purchases AS t
                                    WHERE YEAR(t.date) = {$year_num} AND t.company_id = {$company_id}) AS fuel_cost,
                                    (SELECT 
                                        IFNULL(-SUM(t.repair_misc_amt), 0)
                                    FROM tractor_repairs_misc AS t
                                    WHERE YEAR(t.date) = {$year_num} AND t.company_id = {$company_id}) AS repair_cost) AS t");
        
        array_push($weeks, 'YTD, ' . $year_num);
        if (count($ytd_revenue) != 1) {
            array_push($revenues, 0);
            array_push($ytd_revenues, 0);
        } else {
            array_push($revenues, 0);
            array_push($ytd_revenues, round($ytd_revenue[0]->revenue, 2));
        }

        return view('chart.total_revenue_week', [
            'year_num'  => $year_num,
            'week_num'  => $week_num,
            'weeks'     => json_encode($weeks),
            'data1'     => json_encode($revenues),
            'data2'     => json_encode($ytd_revenues),
        ]);
    }
}
