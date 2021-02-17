<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromView, ShouldAutoSize
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
        $company = Company::where("id", session("company_id"))->first();

        $begin = date('Y-m-'.$company['date_salary']);



        // if($company['date_salary'] > date('d')){
        if($company['date_salary'] > date('d')){
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
        }])->get();

        // return $employee;

        foreach($employee as $key => $value){
            $sum_ovense = 0;
            $sum_holiday_paid = 0;
            $sum_salary_cut = 0;

            foreach($value['ovense'] as $key1 => $value1){
                // return $value1['punishment'];
                $sum_ovense += $value1['punishment'];
                $employee[$key]['punishment_total'] = $sum_ovense;
                // $employee[$key]['salary_fix'] = $value['salary'] - $sum_ovense;
            }

            foreach($value['holiday_paid'] as $key2 => $value2) {
                $sum_holiday_paid += $value2['charge'];
                $employee[$key]['holiday_paid_total'] = $sum_holiday_paid;
            }

            foreach($value['salary_cut'] as $key3 => $value3) {
                $sum_salary_cut += $value3['value'];
                $employee[$key]['salary_cut_total'] = $sum_salary_cut;
            }

            $employee[$key]['salary_fix'] = $value['salary'] - $sum_ovense - $sum_holiday_paid - $sum_salary_cut;

        // }

        }

        return view("employee.excel", [
            "employee" => $employee,
        ]);

    }

    public function model(array $row)
    {
        return new Employee([
            'name'  => $row['name'],
            'email' => $row['email'],
            'at'    => $row['at_field'],
        ]);
    }
}
