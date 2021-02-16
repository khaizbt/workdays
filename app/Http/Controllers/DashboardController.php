<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LeaveDate;
use App\Models\Event;
use DatePeriod;
use App\Models\WorkDay;
use DateInterval;
use Auth;
use Storage;
use Image;
use DateTime;


class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // return Auth::user()->unreadNotifications[0];
        // foreach(Auth::user()->unreadNotifications as $key => $value){
        //     return $value['data']['message'];
        // }
        $user = User::where('id', Auth::id())->first();

        if($user['level'] == 2) {
            $company = Company::where('id_user', $user['id'])->first();
            if($company)
                session(["company_id" => $company['id']]);
            else
                session(["company_id" => 1]);

        } elseif ($user['level'] == 3){
            $employee = Employee::where("user_id", Auth::id())->first();

            session(["company_id" => $employee['company_id']]);
        }

        return view('admin.dashboard.index');
    }

    public function getFile($file){
        $file_storage = str_replace('+', '/', $file);
        $file = Storage::disk('local')->get($file_storage);

        return Image::make($file)->response();
    }

    public function countWorking($startDate){
        $begin = strtotime($startDate);
        $end_date =  strtotime(date('Ymd',strtotime('+1 month',$begin)));
        $last_day = date('Ymd',strtotime('-1 days',$end_date));
        $end   = strtotime($last_day);
            $no_days  = 0;
            while ($begin <= $end) {
                $what_day = date("N", $begin);
                if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
                    $no_days++;
                $begin += 86400; // +1 day
            }
        $holidays = $this->countHolidays($startDate, $last_day);


        return ($no_days-$holidays);
    }

    public function countHolidays($begin_date, $end_date){
        $begin = new DateTime($begin_date);
        $end = new DateTime($end_date);

        $json = $this->getApiHolidays();

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $date = [];
        $date_begin = strtotime($begin->format('Ymd'));

        // return $date_begin;


        // dd($what_day);
        $what_day = [];
        $no_days  = 0;
        foreach($daterange as $key => $value){
            $date_mix = $value->format("Ymd");
            foreach($json as $key1 => $value1){
                if($key1 == $date_mix){

                $date_holidays = strtotime($key1);
                $is_holidays = date("N", $date_holidays);

                    if(!in_array($is_holidays, [6,7])){
                        $no_days++;
                    }
                }
            }

        }

        return $no_days;
    }

    public function getApiHolidays(){
        $str = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');

        // $holiday = Holiday::where('status', '!=', [3])->where('is_approved', 1)->with('employee')->get();

        $holiday = LeaveDate::whereHas('leave', function($q) {
            $q->where("status", '!=', 3)->where("is_approved", 1);
        })->with('leave.employee')->get();

        $event = Event::where("company_id", session("company_id"))->get();
        $events = [];

        foreach($event as $k => $v) {
            $v['deskripsi'] = $v['event_name'];
            $v['color'] = "#fc0356";
            $v['date'] = $v['time'];

            $events[strtotime($v['created_at'])] = $v;
        }

        $holidays = [];
        foreach($holiday as $key => $val) {
            $val['deskripsi'] = $val['leave']['employee']['name'];
            if($val['leave']['status'] == 1)
                $val['color'] = 'green';
            elseif($val['leave']['status'] == 2)
                $val['color'] = 'purple';
            elseif($val['leave']['status'] == 4)
                $val['status'] = '#a84632';
            // unset($val['employee']);
            $holidays[$val['id']] = $val;

        }

        // return $holidays;


        $holiday_count = count($holiday);


        $json = json_decode($str, true);
        // return $json;
        $cuti = [];
        foreach($json as $row => $value) {
            if($row != "created-at"){
                  $value['date'] = $row;

            $cuti[$row] = $value;
            }
                // unset($row);
            // return $value;

        }

        // return $cuti;

        return $holidays + $cuti + $events;


        return $json;
    }
}
