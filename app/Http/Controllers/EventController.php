<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Event;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\EventSubmit;
use DataTables;
use DB;

class EventController extends Controller
{
    public function index() {
        $is_data_empty = Event::where("company_id", session("company_id"))->get()->count() == 0 ? true : false;

        return view("event.index", compact("is_data_empty"));
    }

    public function data(Request $request) {

        $data = Event::where("company_id", session("company_id"))->get();
        return Datatables::of($data)
        ->addColumn('action', function ($data) {
            return "<a href='".route('event.edit', [Crypt::encrypt($data['id'])])."'><i class='fa fa-edit text-info'></i></a>
            | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
    })
    ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create() {
        return view('event.create');
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        $post['company_id'] = session("company_id");

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
            return redirect('/event')->with(['success' => 'Events has been created']);

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/event')->with(['error' => 'Create Events failed']);

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
                "place" => $post['place']
            ]);

            DB::commit();
        return redirect('/event')->with(['success' => 'Event has been updated']);


        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/event')->with(['error' => 'Update Events failed']);

        }
    }

    public function delete($id){
        return Event::where("id", $id)->where("company_id", session("company_id"))->delete();
    }
}
