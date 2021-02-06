<?php

namespace App\Http\Controllers\Holidays;

use App\Http\Controllers\Controller;
use DateTime;
use DatePeriod;
use Auth;
use DB;
use DateInterval;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Company;
use App\Models\LeaveDate;
use Illuminate\Http\Request;
use App\Notifications\SubmitLeave;
use App\Notifications\AssignLeave;
use App\Notifications\RejectLeave;
use App\Notifications\ApproveLeave;
use Illuminate\Support\Facades\Crypt;

class ManageHolidaysController extends Controller
{
    public function getApiHolidays(){
        $str = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');

        // $holiday = Holiday::where('status', '!=', [3])->where('is_approved', 1)->with('employee')->get();

        $holiday = LeaveDate::whereHas('leave', function($q) {
            $q->where("status", '!=', 3)->where("is_approved", 1);
        })->with('leave.employee')->get();

        // $employee = Employee::get()->groupBy("name");

        // return $employee;

        // return $holiday;
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

        return $holidays + $cuti;


        return $json;
    }

    public function countHolidays($begin_date, $end_date){
        $begin = new DateTime($begin_date);
        $end = new DateTime($end_date);

        $json = $this->getApiHolidays();

        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $date = [];
        $date_begin = strtotime($begin->format('Ymd'));

        $what_day = [];
        $no_days  = 0;
        foreach($daterange as $key => $value){
            $date_mix = $value->format("Ymd");
            foreach($json as $key1 => $value1){
                if($key1 == $date_mix){

                $date_holidays = strtotime($key1);
                $is_holidays = date("N", $date_holidays);

                    if(!in_array($is_holidays, [6,7])){ //TODO Konfigurasi Pengaturan Company untuk Libura dan lain-lain
                        $no_days++;
                    }
                }
            }

        }

        return $no_days;
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

    public function index(Request $request){
        $events = [];
        $holidays = [];

        $data = $this->getApiHolidays();
        //TODO Cuti dalam 1 tahun, dan Karyawan dapat mengajukan Cuti

        foreach($data as $key => $value){
                $time = strtotime($value['date']) ?? strtotime('20190101');
                $events[] = [
                    'title' =>  $value['deskripsi'] ?? 'tahun baru',
                    'start' => date("Y-m-d", $time).' 19:00:00',
                    'url' => 'https://www.google.com/search?q='.($value['deskripsi'] ?? 'https://www.google.com/search?q=tahun+baru'),
                    "color" => $value['color'] ?? '',

                ];
        }

        //TODO Event, Absen dan lain2 ditaruh sini

        return view('calendar.calendar', compact('events'));
    }

    public function indexLeave(){ //Daftar Cuti dan Tidak Masuk kerja
        // pHoli
        $data = Holiday::whereHas("employee", function($q)  {
            $q->where("company_id", session("company_id"));
        })->with('employee')->get();



        return $data;
    }

    public function createLeave() {
        $employee = Employee::where("company_id", session("company_id"))->get();



        return view("leave.create", compact("employee"));
    }

    public function storeLeave(Request $request) {
        $post = $request->except("_token");
        $begin = new DateTime("2021-01-01");
        $end = new DateTime("2021-01-01");
        $end = $end->modify( '+1 day' );

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        // dd($period);
        // foreach($period as $k => $v) {
        //     $post['date2'][$k] = $v->format("Y-m-d");
        // }

        // return $post;
        DB::beginTransaction();
        // try {
            $post['employee_id'] = Crypt::decryptString($post['employee']);
            $save = Holiday::create($post);
            // return $save;
            if($save){
                foreach($period as $k => $v) {
                    $save_date = LeaveDate::create([
                        "leave_id" => $save['id'],
                        "date" =>  $v->format("Y-m-d"),
                    ]);
                }
            }

            $notification = [
                'message' => "Your Leave has been assigned",
                'url' => 'dashboard/pre-assesment',
                'action_status' => 'Pra Penilaian'
            ];
            $save->employee->user->notify(new AssignLeave($notification));
            DB::commit();
            return "success";
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return "fail";
        // }

    }

    public function editLeave($id) {
        $data = Holiday::where("id", $id)->with("employee")->first();

        return view("leave.edit", compact("data"));
    }

    public function updateLeave(Request $request, $id){
        $post = $request->except("_token", 'employee_id');

        DB::beginTransaction();
        try {
            $update = Holiday::update($post);
            DB::commit();
            return "success";
        } catch (\Throwable $th) {
            DB::rollback();
            return "fail";
        }
    }

    public function summitLeave(Request $request) {
        $post = $request->except("_token", "charge", "is_approved");

        DB::beginTransaction();
        try {
            $post['employee_id'] = Auth::id();
            $save = Holiday::create($post);

            $notification = [
                'message' => "Leave has been created",
                'url' => 'dashboard/pre-assesment',
                'action_status' => 'Pra Penilaian'
            ];
            $save->employee->user->notify(new SubmitLeave($notification));
            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    public function approve($id) {
        $data = Holiday::where("id", $id)->update(["is_approved" => 1]);

        $notification = [
            'message' => "Your Leave has been approved",
            'url' => 'dashboard/pre-assesment',
            'action_status' => 'Pra Penilaian'
        ];
        $data->employee->user->notify(new ApproveLeave($notification));
    }

    public function reject($id) {
        $data = Holiday::where("id", $id)->update(['is_approved' => 0]);

        $notification = [
            'message' => "Your Leave has been rejected",
            'url' => 'dashboard/pre-assesment',
            'action_status' => 'Pra Penilaian'
        ];
        $save->employee->user->notify(new RejectLeave($notification));
    }

    public function listMyLeave() {
        $data = Holiday::where("user_id", Auth::id())->get();

        //datatable in here
    }

    public function countHolidaysYear($jumlah = null) { //Menghitung Cuti Bersama dalam satu tahun
        $data = $this->getApiHolidays();

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

        $number_leave = 14-$this->countHolidaysYear();

        $number_leave_employee = Employee::where("user_id", Auth::id())->first();
        // return $number_leave_employee;
        $leave = Holiday::where("employee_id", $number_leave_employee['id'])->where("is_approved", 1)->whereYear("created_at", 2021)->get();

        return $leave;

    }

    // public function
}
//TODO Notifikasi Karyawan Mengajukan Cuti, Terkena Denda, ataupun ada Event,
//TODO Karyawan dapat Slip Gaji.
//TODO Pelanggaran
//TODO Event
//TODO Pengajuan Izin Keluar
