<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

if (!function_exists('get_data_compare')) {
    function get_data_compare($search, $compare_list) {
        $company_id = Auth::user()->company_id;

        $compare_data = DB::select("
                                SELECT
                                    gt.week_name, 
                                    (gt.gross + (CASE WHEN osa.o_s_adjustments IS NULL THEN 0 ELSE osa.o_s_adjustments END) - (CASE WHEN fp.fuel_cost IS NULL THEN 0 ELSE fp.fuel_cost END) - (CASE WHEN trm.repair_cost IS NULL THEN 0 ELSE trm.repair_cost END)) AS revenue, 
                                    gt.miles, 
                                    fp.f_cost AS fuel_cost
                                FROM
                                    (SELECT 
                                        year_num,
                                        week_num,
                                        CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                        SUM(daily_gross_amt) AS gross,
                                        SUM(miles_qty) AS miles
                                    FROM 
                                        linehaul_trips
                                    WHERE
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                        CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                        CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                        company_id = {$company_id}
                                    GROUP BY year_num, week_num
                                    ORDER BY year_num ASC, week_num ASC) AS gt
                                LEFT JOIN
                                    (SELECT 
                                        CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                        SUM(amt) AS o_s_adjustments
                                    FROM 
                                        other_settlement_adjustments
                                    WHERE
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                        CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                        CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                        company_id = {$company_id}
                                    GROUP BY year_num, week_num
                                    ORDER BY year_num ASC, week_num ASC) AS osa
                                ON gt.week_name = osa.week_name
                                LEFT JOIN
                                    (SELECT 
                                        CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                        -SUM(auth_chgbk_net) AS fuel_cost,
                                        SUM(pur_amt) AS f_cost
                                    FROM 
                                        fuel_purchases
                                    WHERE
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                        CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                        CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                        company_id = {$company_id}
                                    GROUP BY year_num, week_num
                                    ORDER BY year_num ASC, week_num ASC) AS fp
                                ON gt.week_name = fp.week_name
                                LEFT JOIN
                                    (SELECT 
                                        CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                        -SUM(repair_misc_amt) AS repair_cost
                                        FROM 
                                        tractor_repairs_misc AS trm
                                    WHERE
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                        CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                        CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                        CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                        company_id = {$company_id}
                                    GROUP BY year_num, week_num
                                    ORDER BY year_num ASC, week_num ASC) AS trm
                                ON gt.week_name = trm.week_name
                                ORDER BY gt.year_num, gt.week_num
                            ");

        $headers = array("week_name" => "Week #");
        foreach ($compare_list as $item) {
            if ($item == 'revenue') {
                $headers[$item] = "Total Revenue";
            }
            if ($item == 'miles-total') {
                $headers[$item] = "Total Miles";
            }
            if ($item == 'fuelcost-total') {
                $headers[$item] = "Total Fuel Cost";
            }
        }
        $total_revenue = 0;
        $total_miles = 0;
        $total_fuelcost = 0;
        foreach ($compare_data as $data) {
            $total_revenue += $data->revenue;
            $total_miles += $data->miles;
            $total_fuelcost += $data->fuel_cost;
        }

        $total = new \stdClass();
        $total->week_name = 'Total';
        $total->revenue = $total_revenue;
        $total->miles = $total_miles;
        $total->fuel_cost = $total_fuelcost;
        array_push($compare_data, $total);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $compare_data;
        return $excel_data;
    }
}

if (!function_exists('get_data_revenue')) {
    function get_data_revenue($search) {
        $company_id = Auth::user()->company_id;

        $grosses            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    SUM(daily_gross_amt) AS gross
                                FROM 
                                    linehaul_trips
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $o_s_adjustments    = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    SUM(amt) AS o_s_adjustments
                                FROM 
                                    other_settlement_adjustments
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $fuels              = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    -SUM(auth_chgbk_net) AS fuel_cost
                                FROM 
                                    fuel_purchases
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $repairs            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    -SUM(repair_misc_amt) AS repair_cost
                                FROM 
                                    tractor_repairs_misc
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
	                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        
        $headers = array();
                
        foreach ($grosses as $gross) {
            if (!in_array($gross->week_name, $headers)) {
                array_push($headers, $gross->week_name);
            }
            $gross->gross = round($gross->gross, 2);
        }
        foreach ($o_s_adjustments as $adjustment) {
            if (!in_array($adjustment->week_name, $headers)) {
                array_push($headers, $adjustment->week_name);
            }
            $adjustment->o_s_adjustments = round($adjustment->o_s_adjustments, 2);
        }
        foreach ($fuels as $fuel) {
            if (!in_array($fuel->week_name, $headers)) {
                array_push($headers, $fuel->week_name);
            }
            $fuel->fuel_cost = round($fuel->fuel_cost, 2);
        }
        foreach ($repairs as $repair) {
            if (!in_array($repair->week_name, $headers)) {
                array_push($headers, $repair->week_name);
            }
            $repair->repair_cost = round($repair->repair_cost, 2);
        }
        
        $revenues = array();
        foreach ($headers as $week) {
            $revenue = 0;
            foreach ($grosses as $gross) {
                if ($gross->week_name == $week) {
                    $revenue += $gross->gross;
                    break;
                }
            }
            foreach ($o_s_adjustments as $adjustment) {
                if ($adjustment->week_name == $week) {
                    $revenue += $adjustment->o_s_adjustments;
                    break;
                }
            }
            foreach ($fuels as $fuel) {
                if ($fuel->week_name == $week) {
                    $revenue -= $fuel->fuel_cost;
                    break;
                }
            }
            foreach ($repairs as $repair) {
                if ($repair->week_name == $week) {
                    $revenue -= $repair->repair_cost;
                    break;
                }
            }

            array_push($revenues, round($revenue, 2));
        }

        $total_revenue = 0;
        foreach($revenues as $revenue) {
            $total_revenue += $revenue;
        }
        
        array_push($headers, 'Total');
        array_push($revenues, $total_revenue);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $revenues;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_total')) {
    function get_data_mile_total($search) {
        $company_id = Auth::user()->company_id;

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                SUM(miles_qty) AS total_miles
                            FROM 
                                linehaul_trips
                            WHERE
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");
        $headers = array();
        foreach ($weeks as $week) {
            if (!in_array($week->week_name, $headers)) {
                array_push($headers, $week->week_name);
            }
        }
        
        $values = array();
        $total_miles = 0;
        foreach ($headers as $week_name) {
            $value = 0;
            foreach ($weeks as $week) {
                if ($week_name == 'WK-' . $week->week_num . ', ' . $week->year_num) {
                    $value = $week->total_miles;
                    $total_miles += $week->total_miles;
                    break;
                };
            }
            $values[] = $value;
        }
        array_push($headers, 'Total');
        array_push($values, $total_miles);
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_driver')) {
    function get_data_mile_driver($search) {
        $company_id = Auth::user()->company_id;

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
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                t.company_id = {$company_id}
                            GROUP BY t.year_num, t.week_num, t.driver_1
                            ORDER BY t.year_num ASC, t.week_num ASC");

        $headers = array();
        $drivers = array();
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;

            if (!in_array($week->week, $headers)) {
                array_push($headers, $week->week);
            }

            if (!array_key_exists($week->driver_id, $drivers)) {
                $drivers[$week->driver_id] = $week->driver_name;
            }
        }
        $values = array();
        foreach ($drivers as $d_key => $d_val) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($weeks as $week) {
                    if ($week->driver_id == $d_key && $week->week == $h_val) {
                        $temp[$h_idx] = $week->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$d_key] = array('driver_name' => $d_val, 'miles' => $temp);
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_vehicle')) {
    function get_data_mile_vehicle($search) {
        $company_id = Auth::user()->company_id;

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                vehicle,
                                SUM(miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            WHERE
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                t.company_id = {$company_id}
                            GROUP BY year_num, week_num, vehicle
                            ORDER BY year_num ASC, week_num ASC");
        $headers = array();
        $vehicles = array();
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;

            if (!in_array($week->week, $headers)) {
                array_push($headers, $week->week);
            }

            if (!in_array($week->vehicle, $vehicles)) {
                array_push($vehicles, $week->vehicle);
            }
        }
        $values = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($weeks as $week) {
                    if ($week->vehicle == $v && $week->week == $h_val) {
                        $temp[$h_idx] = $week->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$v] = $temp;
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_trips_driver')) {
    function get_data_trips_driver($search) {
        $company_id = Auth::user()->company_id;

        $trips = DB::select("
                            SELECT 
                                t.year_num,
                                t.week_num,
                                CONCAT('WK-', t.week_num, ', ', t.year_num) AS week_name,
                                t.driver_1 AS driver_id,
                                d.driver_name,
                                COUNT(*) AS trips 
                            FROM 
                                linehaul_trips AS t 
                            INNER JOIN linehaul_drivers AS d ON d.driver_id = t.driver_1 AND d.company_id = {$company_id}
                            WHERE 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                t.company_id = {$company_id}
                            GROUP BY 
                                t.year_num, t.week_num, t.driver_1 
                            ORDER BY t.year_num, t.week_num, t.driver_1");
        
        $headers = array();
        $drivers = array();
        foreach ($trips as $trip) {
            if (!in_array($trip->week_name, $headers)) {
                array_push($headers, $trip->week_name);
            }

            if (!array_key_exists($trip->driver_id, $drivers)) {
                $drivers[$trip->driver_id] = $trip->driver_name;
            }
        }
        
        $values = array();
        foreach ($drivers as $d_key => $d_val) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($trips as $trip) {
                    if ($trip->driver_id == $d_key && $trip->week_name == $h_val) {
                        $temp[$h_idx] = $trip->trips;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$d_key] = array('driver_name' => $d_val, 'trips' => $temp);
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mpg_vehicle')) {
    function get_data_mpg_vehicle($search) {
        $company_id = Auth::user()->company_id;

        $week_miles = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', t.week_num, ', ', t.year_num) AS week_name,
                                    vehicle,
                                    SUM(miles_qty) AS miles
                                FROM 
                                    linehaul_trips AS t
                                WHERE
                                    CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    t.company_id = {$company_id}
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        
        $week_fuels = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    vehicle,
                                    SUM(qty) AS fuel_qty
                                FROM 
                                    fuel_purchases
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                    company_id = {$company_id}
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        
        $headers = array();
        $vehicles = array();
        foreach ($week_miles as $wm) {
            if (!in_array($wm->week_name, $headers)) {
                array_push($headers, $wm->week_name);
            }

            if (!in_array($wm->vehicle, $vehicles)) {
                array_push($vehicles, $wm->vehicle);
            }
        }
        foreach ($week_fuels as $wf) {
            if (!in_array($wf->week_name, $headers)) {
                array_push($headers, $wf->week_name);
            }

            if (!in_array($wf->vehicle, $vehicles)) {
                array_push($vehicles, $wf->vehicle);
            }
        }
        
        $miles_data = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($week_miles as $wm) {
                    if ($wm->vehicle == $v && $wm->week_name == $h_val) {
                        $temp[$h_idx] = $wm->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $miles_data[$v] = $temp;
        }

        $fuels_data = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($week_fuels as $wf) {
                    if ($wf->vehicle == $v && $wf->week_name == $h_val) {
                        $temp[$h_idx] = $wf->fuel_qty;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $fuels_data[$v] = $temp;
        }
        
        $values = array();
        foreach ($vehicles as $v) {
            $m = $miles_data[$v];
            $f = $fuels_data[$v];

            $value = array();
            foreach ($headers as $h_idx => $h_val) {
                array_push($value, ($f[$h_idx] == 0) ? 0 : round($m[$h_idx] / $f[$h_idx], 1));
            }
            $values[$v] = $value;
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_fuelcost_total')) {
    function get_data_fuelcost_total($search) {
        $company_id = Auth::user()->company_id;

        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                SUM(pur_amt) AS cost
                            FROM 
                                fuel_purchases
                            WHERE
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END)) AND
                                company_id = {$company_id}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");

        $headers = array();
        foreach ($weeks as $week) {
            if (!in_array($week->week_name, $headers)) {
                array_push($headers, $week->week_name);
            }
        }

        $values = array();
        $total_cost = 0;
        foreach ($weeks as $week) {
            array_push($values, $week->cost);
            $total_cost += $week->cost;
        }

        array_push($headers, 'Total');
        array_push($values, $total_cost);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_companies_by_user')) {
    function get_companies_by_user($user) {
        if ($user->role == 1) {
            return DB::table('companies')->get()->all();
        } else {
            return DB::table('companies')->where('id', '=', $user->company_id)->get()->all();
        }
    }
}

if (!function_exists('get_company_name_by_id')) {
    function get_company_name_by_id($company_id) {
        $company = DB::table('companies')->where('id', $company_id)->get()->first();
        if ($company) {
            return $company->name;
        } else {
            return '';
        }
    }
}

if (!function_exists('get_company_logo_by_id')) {
    function get_company_logo_by_id($company_id) {
        $company = DB::table('companies')->where('id', $company_id)->get()->first();
        if ($company) {
            return $company->logo;
        } else {
            return '';
        }
    }
}

if (!function_exists('delete_permissions_by_user')) {
    function delete_permissions_by_user($user_id) {
        if ($user_id) {
            return DB::table('user_permission')->where('user_id', $user_id)->delete();
        }

        return false;
    }
}

if (!function_exists('delete_tasks_by_user')) {
    function delete_tasks_by_user($user_id) {
        if ($user_id) {
            $res = DB::table('user_task')
                        ->where('user_id', $user_id)
                        ->delete();
            $res = DB::table('tasks')
                        ->where('user_id', $user_id)
                        ->update(['user_id' => null]);
            $res = DB::table('tasks')
                        ->where('owner_id', $user_id)
                        ->update(['owner_id' => null]);
            
            return true;
        }

        return false;
    }
}

if (!function_exists('delete_tasks_by_task')) {
    function delete_tasks_by_task($task_id) {
        if ($task_id) {
            $res = DB::table('user_task')->where('task_id', $task_id)->delete();

            return true;
        }

        return false;
    }
}

if (!function_exists('get_day_of_week_from_string')) {
    function get_day_of_week_from_string($str = 'friday') {
        switch(strtolower($str)) {
            case "monday":
                return 1;
            case "tuesday":
                return 2;
            case "wednesday":
                return 3;
            case "thursday":
                return 4;
            case "friday":
                return 5;
            case "saturday":
                return 6;
            case "sunday":
                return 7;
        }

        return 0;
    }
}

if (!function_exists('get_to_date')) {
    /*
    * @param int $payment_date: 1-Monday and 7-Saturday
    * @return to_date from today
    */
    function get_to_date($payment_date = 5, $format = null) {
        $today = Carbon::now();

        $today_date = ($today->dayOfWeek != 0) ? $today->dayOfWeek : 7; // 1-Monday and 7-Saturday

        if ($today_date <= $payment_date) {
            $date = $today->subDays(2 + $today_date);
            return (!empty($format)) ? $date->format($format) : $date;
        }

        $date = $today->subDays($today_date - $payment_date);
        return (!empty($format)) ? $date->format($format) : $date;
    }
}

if (!function_exists('get_from_date')) {
    /*
    * @param int $payment_date: 1-Monday and 7-Saturday
    * @return from_date from today
    */
    function get_from_date($payment_date = 5, $format = null) {
        $date = get_to_date($payment_date);

        return (!empty($format)) ? $date->subDays(6)->format($format) : $date->subDays(6);
    }
}

if (!function_exists('get_payment_date')) {
    /*
    * @param int $payment_date: 1-Monday and 7-Saturday
    * @return from_date from today
    */
    function get_payment_date($payment_date = 5, $format = null) {
        $date = get_to_date($payment_date);

        if (($date->dayOfWeek != 0 ? $date->dayOfWeek : 7) == $payment_date) {
            $date = $date->addDays(7);
        }

        return (!empty($format)) ? $date->addDays((2 + $payment_date) % 7)->format($format) : $date->addDays(2 + $payment_date);
    }
}
