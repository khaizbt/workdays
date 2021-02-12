<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Event;
// use App\Models\EventSubmit;
use App\Notifications\EventSubmit;
use DB;

class EventController extends Controller
{
    public function create() {
        return view('event.create');
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        $post['company_id'] = session("company_id");

        // return $post;
        DB::beginTransaction();
        // try {
            $save = Event::create($post);
            // return session("company_id");
            $employee = Employee::where("company_id", session("company_id"))->get();


            foreach($employee as $key => $value) {
                $notification = [
                    'message' => "New Event on".$post['time'].' at'.$post['place'],
                    'url' => 'event/'.$save['id'],
                    'action_status' => 'Pra Penilaian'
                ];
                $value->user->notify(new EventSubmit($notification));
            }



            DB::commit();
        // return "sukses";
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return "gagal";
        // }
    }
}
