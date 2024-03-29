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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ManageHolidaysController extends Controller
{
    public function getApiHolidays(){
        $str = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');

        // return $str;
        $json = json_decode($str, true);

        return $json;
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



        foreach($this->getApiHolidays() as $key => $value){
                $time = strtotime($key) ?? strtotime('20190101');
                $events[] = [
                    'title' =>  $value['deskripsi'] ?? 'tahun baru',
                    'start' => date("Y-m-d", $time).' 19:00:00',
                    'url' => 'https://www.google.com/search?q='.($value['deskripsi'] ?? 'https://www.google.com/search?q=tahun+baru')

                ];
        }

        //TODO Event, Absen dan lain2 ditaruh sini

        return view('calendar.calendar', compact('events'));
    }

    public function indexLeave(){ //Daftar Cuti dan Tidak Masuk kerja
        // pHoli
        //TODO CRUD Cuti
    }

    public function createLeave() {
        $employee = Employee::whereHas("company", function($q){
            $q->where("id_user", Auth::id());
        })->get();

        return view("leave.create", compact("employee"));
    }

    public function storeLeave(Request $request) {
        $post = $request->except("_token");
        // return $post;
        DB::beginTransaction();
        // try {
            $post['employee_id'] = Crypt::decryptString($post['employee']);
            $save = Holiday::create($post);
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

}
//TODO Notifikasi Karyawan Mengajukan Cuti, Terkena Denda, ataupun ada Event,
//TODO Karyawan dapat Slip Gaji.
