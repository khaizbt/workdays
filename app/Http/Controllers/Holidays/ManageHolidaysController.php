<?php

namespace App\Http\Controllers\Holidays;

use App\Http\Controllers\Controller;
use DateTime;
use DatePeriod;
use Auth;
use DB;
use DateInterval;
use DataTables;
use PDF;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Company;
use App\Models\LeaveDate;
use App\Models\WorkDay;
use App\Models\Ovense;
use App\Models\Event;
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

        if(Auth::user()->hasRole('Admin')){
            $holiday = LeaveDate::whereHas('leave', function($q) {
                $q->where("status", '!=', 3)->where("is_approved", 1)->whereHas('employee', function($que){
                    $que->where('company_id', session('company_id'));
                });
            })->with('leave.employee')->get();
        } elseif(Auth::user()->hasRole('User')){
            $holiday = LeaveDate::whereHas('leave', function($q) {
                $q->where("status", '!=', 3)->where("is_approved", 1)->whereHas('employee', function($que){
                    $que->where('user_id', Auth::id());
                });
            })->with('leave.employee')->get();
        }

        if(Auth::user()->hasRole('Admin')){
            $ovense = Ovense::whereHas('employee', function($q) {
                $q->where('company_id', session('company_id'));
            })->with('employee')->get();
        } elseif(Auth::user()->hasRole('User')){
            $ovense = Ovense::whereHas('employee', function($q) {
                $q->where('user_id', Auth::id());
            })->with('employee')->get();
        }
        $cuti = [];

        $json = json_decode($str, true);

        foreach($json as $row => $value) {
            if($row != "created-at"){
                    $value['date'] = $row;

            $cuti[$row] = $value;
            }
                // unset($row);
            // return $value;

        }

        if(!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')){
            return $cuti;
        }




        $offense = [];
        $events = [];
        $holidays = [];

        $event = Event::where("company_id", session("company_id"))->get();


        foreach($event as $k => $v) {
            $v['deskripsi'] = $v['event_name'];
            $v['color'] = "#fc0356";
            $v['date'] = $v['time'];
            $v['url'] =  (Auth::user()->hasRole('Admin')) ? 'event' : 'event/my-event';
            $events[strtotime($v['created_at'])] = $v;
        }

        foreach($holiday as $key => $val) {
            $val['deskripsi'] = $val['leave']['employee']['name'];
            if($val['leave']['status'] == 1)
                $val['color'] = 'green';
            elseif($val['leave']['status'] == 2)
                $val['color'] = 'purple';
            elseif($val['leave']['status'] == 4)
                $val['status'] = '#a84632';
            // unset($val['employee']);
            $val['url'] =  (Auth::user()->hasRole('Admin')) ? 'leave' : 'leave/my-leave';;
            $holidays[$val['id']] = $val;

        }


        foreach($ovense as $key_ovense => $val_ovense) {
            $val_ovense['deskripsi'] = $val_ovense['employee']['name'];
            // $offense[$row] =
                $val_ovense['color'] = 'red';
                $val_ovense['url'] =  (Auth::user()->hasRole('Admin')) ? 'ovense' : 'ovense/my-ovense';;
            $offense[date('YmdHi', strtotime($val_ovense['created_at']))] = $val_ovense;

        }

        // return $holidays;

        // return $holidays;


        // $holiday_count = count($holiday);


        // // return $json;

        // foreach($json as $row => $value) {
        //     if($row != "created-at"){
        //             $value['date'] = $row;

        //     $cuti[$row] = $value;
        //     }
        //         // unset($row);
        //     // return $value;

        // }

        // return $cuti;

        return $holidays + $cuti + $events + $offense;


        return $json;
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

                    if(!in_array($is_holidays, [6,7])){ //TODO Masih HardCod
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


    public function index(Request $request){
        $events = [];
        $holidays = [];

        $data = $this->getApiHolidays();

        // return $data;

        foreach($data as $key => $value){
                $time = strtotime($value['date']) ?? strtotime('20190101');
                $events[] = [
                    'title' =>  $value['deskripsi'] ?? 'tahun baru',
                    'start' => date("Y-m-d", $time).' 19:00:00',
                    'url' => $value['url'] ?? 'https://www.google.com/search?q='.($value['deskripsi'] ?? 'https://www.google.com/search?q=tahun+baru'),
                    "color" => $value['color'] ?? '',

                ];
        }


        return view('calendar.calendar', compact('events'));
    }

    public function indexLeave(){ //Daftar Cuti dan Tidak Masuk kerja
        // pHoli
        $holiday = Holiday::whereHas("employee", function($q)  {
            $q->where("company_id", session("company_id"));
        })->where('is_approved', 2)->with('employee')->get();

        $is_data_empty = $holiday->count() == -100 ? true : false;

        return view('leave-new.index', ['is_data_empty' => $is_data_empty, "holiday" => $holiday]);
    }

    public function dataLeave(Request $request){
        // if($request->ajax()) {
            $data = Holiday::whereHas("employee", function($q){
                $q->where("company_id", session("company_id"));
            })->with("employee")->get();
            return Datatables::of($data)
            ->addColumn('action', function ($data) {
                return "<a href='".route('leave.edit', [Crypt::encrypt($data['id'])])."'><i class='fa fa-edit text-info'></i></a>
                | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })->addColumn('status_str', function($row){
                return ($row['status'] == 1) ? "Cuti" : "Lainnya";
            })->addColumn('approved_str', function($row){
                return ($row['is_approved'] == 1) ? "Approved" : "Rejected";
            })->addColumn('charge_str', function($row){
                return "Rp.".number_format($row['charge']);
            })
            ->addIndexColumn()
                ->rawColumns(['action', 'status_str', 'approved_str'])
                ->make(true);
        // }
    }

    public function createLeave() {
        $employee = Employee::where("company_id", session("company_id"))->get();



        return view("leave-new.create", compact("employee"));
    }

    public function storeLeave(Request $request) {
        $post = $request->except("_token");

        $validator = Validator::make($request->all(), [
            'leave_name' => 'required|max:255',
            'employee' => 'required',
            'date_start'    => 'required',
            'date_end' => 'required',
            'employee' => 'required',
            'is_approved' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('leave/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $begin = new DateTime($post['date_start']);
        $end = new DateTime($post['date_end']);
        $end = $end->modify( '+1 day' );

        $post['charge'] = str_replace(['Rp', " ", "."], "", $post['charge']);



        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $startTimeStamp = strtotime($post['date_start']);
        $endTimeStamp = strtotime($post['date_end']);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $numberDays = intval($numberDays+1);

        $company = Company::where('id_user', Auth::id())->first();

        if($numberDays > $company['maximum_leave']){
            return redirect("/leave")->withErrors(["maximum leave is ".$company['maximum_leave']." days"]);
        }

        DB::beginTransaction();
        try {
            $post['employee_id'] = Crypt::decryptString($post['employee']);
            $post['charge'] = str_replace(["Rp", " ", ".",","], "", $post['charge']);
            if($post['charge'] == "")
                $post['charge'] = 0;
            // return $post;
            $save = Holiday::create($post);

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
                'url' => 'leave/my-leave',
                'action_status' => 'Pra Penilaian'
            ];
            $save->employee->user->notify(new AssignLeave($notification));
            DB::commit();
            return redirect("/leave")->withSuccess(["Leave has been assigned"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect("/leave")->withErrors(["Assign Leave Failed #RT438"]);
        }

    }

    public function editLeave($id) {
        $data = Holiday::where("id", Crypt::decrypt($id))->with(["employee", 'leave_date_all'])->first();
        $employee = Employee::where("company_id", session("company_id"))->get();
        return view("leave-new.edit", compact("data", "employee"));
    }

    public function updateLeave(Request $request, $id){
        $post = $request->except("_token", 'employee_id');
        $validator = Validator::make($request->all(), [
            'leave_name' => 'required|max:255',
            'employee' => 'required',
            'date_start'    => 'required',
            'date_end' => 'required',
            'employee' => 'required',
            'is_approved' => 'required',
            "status" => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('leave/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        DB::beginTransaction();
        try {
            $begin = new DateTime($post['date_start']);
            $end = new DateTime($post['date_end']);
            $end = $end->modify( '+1 day' );

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $post['employee_id'] = Crypt::decryptString($post['employee']);

            $startTimeStamp = strtotime($post['date_start']);
            $endTimeStamp = strtotime($post['date_end']);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays+1);
            $post['charge'] = str_replace(['Rp', " ", "."], "", $post['charge']);

            $company = Company::where('id_user', Auth::id())->first();

            if($numberDays > $company['maximum_leave']){
                return redirect("/leave")->withErrors(["maximum leave is ".$company['maximum_leave']." days"]);
            }
            // return $post;
            $id = Crypt::decryptString($id);
            $update = Holiday::where("id", $id)->update([
                "leave_name" => $post['leave_name'],
                "employee_id" => $post['employee_id'],
                "status" => $post['status'],
                "charge" => $post['charge'],
                "date_start" => $post['date_start'],
                "date_end" => $post['date_end'],
                "is_approved" => $post['is_approved']
            ]);

            if($update){
                LeaveDate::where("leave_id", $id)->delete();
                foreach($period as $k => $v) {
                    $save_date = LeaveDate::create([
                        "leave_id" => $id,
                        "date" =>  $v->format("Y-m-d"),
                    ]);
                }
            }


            DB::commit();

            return redirect("/leave")->withSuccess(["Leave has been updated"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect("/leave")->withErrors(["Update Leave Failed #RT435"]);
        }
    }

    public function submitIndex() {
        return view("leave-new.submit");
    }

    public function summitLeave(Request $request) {
        $post = $request->except("_token", "charge", "is_approved", "employee_id");
        $validator = Validator::make($request->all(), [
            'leave_name' => 'required|max:255',
            'date_start'    => 'required',
            'date_end' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('leave/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        $begin = new DateTime($post['date_start']);
        $end = new DateTime($post['date_end']);
        $end = $end->modify( '+1 day' );

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $startTimeStamp = strtotime($post['date_start']);
        $endTimeStamp = strtotime($post['date_end']);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $numberDays = intval($numberDays+1);

        $company = Company::where('id', session('company_id'))->first();

        if($numberDays > $company['maximum_leave']){
            return redirect("/leave")->with("success", "maximum leave is ".$company['maximum_leave'])." days";
        }
        DB::beginTransaction();
        try {
            $post['employee_id'] = Employee::where("user_id", Auth::id())->first()->id;
            $post['is_approved'] = 2;
            $save = Holiday::create($post);
            if($save){
                foreach($period as $k => $v) {
                    $save_date = LeaveDate::create([
                        "leave_id" => $save['id'],
                        "date" =>  $v->format("Y-m-d"),
                    ]);
                }
            }

            $company  = Company::where("id", session("company_id"))->first();
            $notification = [
                'message' => "Leave has been Submitted",
                'url' => 'leave',
                'action_status' => 'Pra Penilaian'
            ];
            $company->user->notify(new SubmitLeave($notification));
            DB::commit();

            return redirect("/leave/my-leave")->with("success", "Leave has been submitted");
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect("/leave/my-leave")->with("error", "Submit Leave Failed");
        }
    }

    public function approve(Request $request,$id) {
        $data = Holiday::where("id", $id)->first();

        $data->update(["is_approved" => 1, "charge" => $request->charge ?? null]);
        $notification = [
            'message' => "Your Leave has been approved",
            'url' => 'leave/my-leave',
            'action_status' => 'Pra Penilaian'
        ];
        $data->employee->user->notify(new ApproveLeave($notification));

        return redirect("/leave")->with("success", "Leave has been approved");
    }

    public function reject(Request $request,$id) {
        // return $request;
        $data = $data = Holiday::where("id", $id)->first();

        $data->update(['is_approved' => 0, "reject_reason" => $request->reject_reason]);

        // return $data;

        $notification = [
            'message' => "Your Leave has been rejected! Because ".$request->reject_reason,
            'url' => 'leave/my-leave',
            'action_status' => 'Pra Penilaian'
        ];
        $data->employee->user->notify(new RejectLeave($notification));

        // return redirect("/leave")->with("success", "Leave has been Rejected");
    }

    public function listMyLeave() {
        $employee_id = Employee::where("user_id", Auth::id())->first()->id;
        $data = Holiday::where("employee_id", $employee_id)->orderBy("date_start", "desc")->get();

        return Datatables::of($data)
        ->addColumn("approved", function($q){
            return ($q['is_approved'] == 1) ? "approved" : (($q['is_approved'] == 0) ? "rejected" : "pending");
        })->addColumn("status_str", function($q){
            return ($q['status'] == 1) ? "cuti" : (($q['status'] == 2) ? "sakit" : (($q['status'] == 3) ? "alpha" : "izin"));
        }) ->addColumn('action', function ($data) {
            return "<a href='".route('company.edit', [$data['id']])."'><i class='fa fa-edit text-info'></i></a>";
        })
        ->addIndexColumn()
        ->rawColumns(["status_str", "action"])
            ->make(true);
    }

    public function myLeave() {
        $employee_id = Employee::where("user_id", Auth::id())->first()->id;
        $data = Holiday::where("employee_id", $employee_id)->orderBy("date_start", "desc")->get()->count() == 0 ? true : false;

        return view('leave-new.myleave', ['is_data_empty' => $data]);;


    }


    public function deleteLeave($id) {
        return Holiday::where("id", $id)->delete();
    }



    public function countSalaryEmployeeAll($month = null) {
        $company = Company::where("id", session("company_id"))->first();


        for($i = 1; $i <= 12; $i++){
            if($month == $i) {
                $date = date('Y-'.$i.'-'.$company['date_salary']);
                $begin = date('Y-m-d',strtotime('-1 days',strtotime($date)));
                break;
            } elseif($month == null) {
                $begin = date('Y-m-'.$company['date_salary']);
            }
        }


        // if($company['date_salary'] > date('d')){
        if($company['date_salary'] > date('d') && $month == null){
            $begin_date = date('Y-m-'.$company['date_salary']);
            $date_str =  strtotime(date('Ymd',strtotime('-1 month',strtotime($begin))));
            $begin = date('Y-m-d',strtotime('-1 days',$date_str));
        }

            // return $begin;
        $end_date =  strtotime(date('Ymd',strtotime('+1 month',strtotime($begin))));
        $last_day = date('Y-m-d',strtotime('-1 days',$end_date));
        $end   = strtotime($last_day);

        $employee = Employee::where('company_id', session("company_id"))->with(['ovense' => function($q) use($begin, $last_day){
            $q->whereBetween("date", [$begin, $last_day]);
        }])->with(['holiday_paid' => function($query) use($begin, $last_day){
            $query->whereHas("leave_date", function($que) use($begin, $last_day){
                $que->whereBetween("date", [$begin, $last_day]);
            });
        }])->with(['salary_cut' => function($q)  use($begin, $last_day){
            $q->whereBetween("created_at", [$begin, $last_day]);
        }])->get();

        // return $employee;

        foreach($employee as $key => $value){
            $sum_ovense = 0;
            $sum_holiday_paid = 0;
            $sum_salary_cut = 0;

            foreach($value['ovense'] as $key1 => $value1){
                // return $value1['punishment'];
                $sum_ovense += $value1['punishment'];
                // $employee[$key]['salary_fix'] = $value['salary'] - $sum_ovense;
            }
            $employee[$key]['punishment_total'] = $sum_ovense;


            foreach($value['holiday_paid'] as $key2 => $value2) {
                $sum_holiday_paid += $value2['charge'];
            }
            $employee[$key]['holiday_paid_total'] = $sum_holiday_paid;


            foreach($value['salary_cut'] as $key3 => $value3) {
                $sum_salary_cut += $value3['value'];
            }
            $employee[$key]['salary_cut_total'] = $sum_salary_cut;


            $employee[$key]['salary_fix'] = $value['salary'] - $sum_ovense - $sum_holiday_paid - $sum_salary_cut;

        // }

        }

        return Datatables::of($employee)
            ->addColumn('action', function ($data) {
                return "<a href='".route('salary.show', [Crypt::encrypt($data['user_id'])])."'><i class='fa fa-eye text-info'></i></a>";
            })
            // ->addColumn('holiday_paid_str', function($data){
            //     return
            // })
            ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);

    }

    public function countSalaryEmployee($month = null) {
        $company = Company::where("id", session("company_id"))->first();


        for($i = 1; $i <= 12; $i++){
            if($month == $i) {
                $date = date('Y-'.$i.'-'.$company['date_salary']);
                $begin = date('Y-m-d',strtotime('-1 days',strtotime($date)));
                break;
            } elseif($month == null) {
                $begin = date('Y-m-'.$company['date_salary']);
            }
        }

        if($company['date_salary'] > date('d') && $month == null){
            $begin_date = date('Y-m-'.$company['date_salary']);
            $date_str =  strtotime(date('Ymd',strtotime('-1 month',strtotime($begin))));
            $begin = date('Y-m-d',strtotime('-1 days',$date_str));
        }
            // return $begin;
        $end_date =  strtotime(date('Ymd',strtotime('+1 month',strtotime($begin))));
        $last_day = date('Y-m-d',strtotime('-1 days',$end_date));
        $end   = strtotime($last_day);

        // return $last_day;
        $employee = Employee::where('user_id', Auth::id())->with(['ovense' => function($q) use($begin, $last_day){
            $q->whereBetween("date", ["2021-02-01", $last_day]);
        }])->with(['holiday_paid' => function($query) use($begin, $last_day){
            $query->whereHas("leave_date", function($que) use($begin, $last_day){
                $que->whereBetween("date", [$begin, $last_day]);
            });
        }])->with(['salary_cut' => function($q)  use($begin, $last_day){
            $q->whereBetween("created_at", [$begin, $last_day]);
        }])->first();

        // foreach($employee as $key => $value){
            $sum_ovense = 0;
            $sum_holiday_paid = 0;

            foreach($employee['ovense'] as $key1 => $value1){
                // return $value1['punishment'];
                $sum_ovense += $value1['punishment'];
                $employee['punishment_total'] = $sum_ovense;
                // $employee['salary_fix'] = $value['salary'] - $sum_ovense;
            }



            foreach($employee['holiday_paid'] as $key2 => $value2) {
                $sum_holiday_paid += $value2['charge'];
                $employee['holiday_paid_total'] = $sum_holiday_paid;
            }

            $employee['salary_fix'] = $employee['salary'] - $sum_ovense - $sum_holiday_paid;

        // }

        // }
        return $employee;

    }

    public function detailSalary($employee) {
        $employee = Crypt::decrypt($employee);

        $company = Company::where("id", session("company_id"))->first();

        $month = null;
        for($i = 1; $i <= 12; $i++){
            if($month == $i) {
                $date = date('Y-'.$i.'-'.$company['date_salary']);
                $begin = date('Y-m-d',strtotime('-1 days',strtotime($date)));
                break;
            } elseif($month == null) {
                $begin = date('Y-m-'.$company['date_salary']);
            }
        }

        if($company['date_salary'] > date('d') && $month == null){
            $begin_date = date('Y-m-'.$company['date_salary']);
            $date_str =  strtotime(date('Ymd',strtotime('-1 month',strtotime($begin))));
            $begin = date('Y-m-d',strtotime('-1 days',$date_str));
        }
            // return $begin;
        $end_date =  strtotime(date('Ymd',strtotime('+1 month',strtotime($begin))));
        $last_day = date('Y-m-d',strtotime('-1 days',$end_date));
        $end   = strtotime($last_day);


        $employee = Employee::where('user_id', $employee)->with(['ovense' => function($q) use($begin, $last_day){
            $q->whereBetween("date", [$begin, $last_day]);
        }])->with(['holiday_paid' => function($query) use($begin, $last_day){
            $query->whereHas("leave_date", function($que) use($begin, $last_day){
                $que->whereBetween("date", [$begin, $last_day]);
            });
        }])->with(['salary_cut' => function($q)  use($begin, $last_day){
            $q->whereBetween("created_at", [$begin, $last_day]);
        }])->first();

        // foreach($employee as $key => $value){
            $sum_ovense = 0;
            $sum_holiday_paid = 0;
            $sum_salary_cut = 0;

            foreach($employee['ovense'] as $key1 => $value1){
                // return $value1['punishment'];
                $sum_ovense += $value1['punishment'];
                $employee['punishment_total'] = $sum_ovense;
                // $employee['salary_fix'] = $value['salary'] - $sum_ovense;
            }

            foreach($employee['holiday_paid'] as $key2 => $value2) {
                $sum_holiday_paid += $value2['charge'];
                $employee['holiday_paid_total'] = $sum_holiday_paid;
            }

            foreach($employee['salary_cut'] as $key3 => $value3) {
                $sum_salary_cut += $value3['value'];
                $employee['salary_cut_total'] = $sum_salary_cut;
            }

            $employee['salary_fix'] = $employee['salary'] - $sum_ovense - $sum_holiday_paid - $sum_salary_cut;

        // }
            // return $employee;
        // }
            return view("employee.detail", compact("employee"));
    }


    public function exportPdf($employee, $month = null) {
        $employee = Crypt::decrypt($employee);
        $company = Company::where("id", session("company_id"))->first();


        for($i = 1; $i <= 12; $i++){
            if($month == $i) {
                $date = date('Y-'.$i.'-'.$company['date_salary']);
                $begin = date('Y-m-d',strtotime('-1 days',strtotime($date)));
                break;
            } elseif($month == null) {
                $begin = date('Y-m-'.$company['date_salary']);
            }
        }

        if($company['date_salary'] > date('d') && $month == null){
            $begin_date = date('Y-m-'.$company['date_salary']);
            $date_str =  strtotime(date('Ymd',strtotime('-1 month',strtotime($begin))));
            $begin = date('Y-m-d',strtotime('-1 days',$date_str));
        }
            // return $begin;
        $end_date =  strtotime(date('Ymd',strtotime('+1 month',strtotime($begin))));
        $last_day = date('Y-m-d',strtotime('-1 days',$end_date));
        $end   = strtotime($last_day);


        $employee = Employee::where('user_id', $employee)->with(['ovense' => function($q) use($begin, $last_day){
            $q->whereBetween("date", ["2021-02-01", $last_day]);
        }])->with(['holiday_paid' => function($query) use($begin, $last_day){
            $query->whereHas("leave_date", function($que) use($begin, $last_day){
                $que->whereBetween("date", [$begin, $last_day]);
            });
        }])->with(['salary_cut' => function($q)  use($begin, $last_day){
            $q->whereBetween("created_at", [$begin, $last_day]);
        }])->with('company')->first();

        // foreach($employee as $key => $value){
            $sum_ovense = 0;
            $sum_holiday_paid = 0;
            $sum_salary_cut = 0;

            foreach($employee['ovense'] as $key1 => $value1){
                // return $value1['punishment'];
                $sum_ovense += $value1['punishment'];
                $employee['punishment_total'] = $sum_ovense;
                // $employee['salary_fix'] = $value['salary'] - $sum_ovense;
            }

            foreach($employee['holiday_paid'] as $key2 => $value2) {
                $sum_holiday_paid += $value2['charge'];
                $employee['holiday_paid_total'] = $sum_holiday_paid;
            }

            foreach($employee['salary_cut'] as $key3 => $value3) {
                $sum_salary_cut += $value3['value'];
                $employee['salary_cut_total'] = $sum_salary_cut;
            }

            $employee['salary_fix'] = $employee['salary'] - $sum_ovense - $sum_holiday_paid - $sum_salary_cut;

            // return $employee;
        // }
            // return view("employee.slipgaji", compact('employee'));
        // }
        $pdf = PDF::loadview('employee.slipgaji',['employee'=>$employee]);
	    return $pdf->stream('Slip Gaji-'.$employee['name'].'.pdf');
    }

    public function cutiBersama() {
         $str = file_get_contents('https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json');

        // return $str;
        $json = json_decode($str, true);

        return $json;
    }

    // public function
}
