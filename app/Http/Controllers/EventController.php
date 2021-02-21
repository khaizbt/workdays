<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Event;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\EventSubmit;
use DataTables;
use DB;
use Validator;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index() {
        $is_data_empty = Event::where("company_id", session("company_id"))->get()->count() == 0 ? true : false;

        return view("event.index", compact("is_data_empty"));
    }

    public function data(Request $request) {
        if($request->ajax()){
            $data = Event::where("company_id", session("company_id"))->get();
            return Datatables::of($data)
            ->addColumn('action', function ($data) {
                return "<a href='".route('event.edit', [Crypt::encrypt($data['id'])])."'><i class='fa fa-edit text-info'></i></a>
                | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
        })->addColumn('link', function($row){
            return  ($row['maps'] != null) ? "<a href='".$row['maps']."' target='_blank'>Link Maps</a>" :'-';
        })
        ->addIndexColumn()
            ->rawColumns(['action', 'link'])
            ->make(true);
        }
    }

    public function create() {
        return view('event.create');
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        $post['company_id'] = session("company_id");
        $validator = Validator::make($post, [
            'event_name' => 'required|max:100',
            // 'notes' => 'required|min:10',
            'note' => 'required',
            'time' => 'required',
            'place' =>'required',

        ]);

        if ($validator->fails()) {
            return redirect('salary-cut')
                        ->withErrors($validator);
        }
        // return $post;
        DB::beginTransaction();
        try {
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

//TODO Icon, Cek Typo Header dan Cek Redirect if Success or Error

            DB::commit();
            return redirect('/event')->withSuccess(['Events has been created']);

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/event')->withErrors(['Create Events failed']);

        }
    }

    public function edit($id) {
        $data = Event::where("id", Crypt::decrypt($id))->where("company_id", session("company_id"))->first();

        return view("event.edit", compact('data'));
    }

    public function update(Request $request, $id) {
        $post = $request->except("id", "_token", "company_id");

        DB::beginTransaction();
        try {
            $data = Event::where("id", Crypt::decrypt($id))->where("company_id", session("company_id"))->update([
                "note" => $post['note'],
                "event_name" => $post['event_name'],
                "time" => $post['time'],
                "place" => $post['place'],
                "maps" => $post['maps']
            ]);

            DB::commit();
        return redirect('/event')->withSuccess(['Event has been updated']);


        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/event')->withErrors(['Update Events failed']);

        }
    }

    public function delete(Request $request, $id){
        if($request->ajax())
            return Event::where("id", $id)->where("company_id", session("company_id"))->delete();
    }

    public function myEvent() {
        $event = Event::where('company_id', session("company_id"))->get();

        $is_data_empty = $event->count() == 0 ? true : false;

        return view("event.myevent", compact("event", 'is_data_empty'));
    }
}
