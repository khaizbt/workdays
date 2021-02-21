<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\LeaveDate;
use App\Models\Ovense;
use App\Models\Event;
use App\Models\Holiday;
use DatePeriod;
use App\Models\WorkDay;
use DateInterval;
use Auth;
use Storage;
use Image;
use DateTime;
use App\Http\Controllers\Holidays\ManageHolidaysController as HH;


class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::where('id', Auth::id())->first();

        if(!$user->hasRole("Admin") && !$user->hasRole("User"))
            return view('admin.dashboard.index');

            // return
        $data;
        if($user['level'] == 2) {
            $company = Company::where('id_user', $user['id'])->first();

            // return $company;
            if($company){
                session(["company_id" => $company['id']]);
                $data['count_working'] = self::countWorking();
                $data['count_employee'] = self::employeeCount();
                $data['count_leave'] = self::countLeave();
                $data['gajian'] = date('l, d F Y', strtotime(date('Y-m-'.$company['date_salary'])));
            }
            else
                session(["company_id" => 0]);

        } elseif ($user['level'] == 3){

            // return $company;

            $employee = Employee::where("user_id", Auth::id())->first();
            $company = Company::where('id', $employee['company_id'])->first();

            session(["company_id" => $employee['company_id']]);
            $data['count_leave'] = self::countHolidaysEmployee();
            $data['count_working'] = self::countWorking();
            $data['ovense'] = self::ovense();
            $data['gajian'] = date('l, d F Y', strtotime(date('Y-m-'.$company['date_salary'])));
        }

        // return session()->all();

        return view('admin.dashboard.index', compact('data'));
    }

    public function getFile($file){
        // $file_storage = str_replace('+', '/', $file);
        // $file = Storage::disk('local')->get($file_storage);

        // return Image::make($file)->response();

        $image = Image::make(Storage::disk('local')->get($file));
        return Response::make($image->encode('png'), 200, ['Content-Type' => 'image/png']);
    }

    public function countHolidays($begin_date, $end_date){
        $begin = new DateTime($begin_date);
        $end = new DateTime($end_date);

        $json = $this->cutiBersama();

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $date = [];
        $date_begin = strtotime($begin->format('Ymd'));

        // $date_work = WorkDay::where('id_company', session('company_id'))->get()->toArray();

        // $days = [1, 2,3,4,5,6];

        // $day = array_splice($days,1, array_column($date_work, 'days'));



        // return $days;
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

    public function countWorking(){
        $company = Company::where('id', session('company_id'))->first();
        $startDate = date('Ym'.$company['date_salary']);
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

    public function cutiBersama() {
        $str = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');

       // return $str;
       $json = json_decode($str, true);

       return $json;
   }

   public function countHolidaysYear($jumlah = null) { //Menghitung Cuti Bersama dalam satu tahun
        $data = $this->cutiBersama();

        $cuti = [];
        foreach($data as $key => $val) {
            // return $val;
            if(substr($key,0,4) == date("Y")) {
                if(strpos($val['deskripsi'], "Cuti Bersama") !== false) {
                    $cuti[$key] = $val;
                }
            }
        }

        if($jumlah != null)
            return $cuti;

        return count($cuti);
    }

    public function countHolidaysEmployee() {
        $company = Company::where("id", session("company_id"))->first();

        $number_leave = $company['number_leave']-$this->countHolidaysYear();

        $employee = Employee::where('user_id', Auth::id())->first();

        // return $number_leave_employee;
        $leave = LeaveDate::whereYear("date", date('Y'))->whereHas('leave', function($q) use($employee){
            $q->where('employee_id', $employee['id'])->where('is_approved', 1);
        })->get()->count();
        // $leave = Holiday::where("employee_id", $number_leave_employee['id'])->where("is_approved", 1)->with()
        // ->whereYear("created_at", 2021)->get();

        return $number_leave-$leave;

    }

    public function ovense(){
        $ovense = Ovense::whereYear('date', date('Y'))->whereHas('employee', function($q){
            $q->where('user_id', Auth::id());
        })->get();

        return $ovense->count();
    }

    public function employeeCount(){
        $employee = Employee::whereHas('company', function($q){
            $q->where('id_user', Auth::id());
        })->get()->count();

        return $employee;
    }

    public function countLeave() {
        $leave = Holiday::whereMonth('date_start', date('m'))->where('is_approved', 1)->whereHas('employee', function($q){
            $q->where('company_id', session('company_id'));
        })->get()->count();

        return $leave;
    }



}
