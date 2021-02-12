<?php
namespace App\Helpers;

use File;
use Storage;
use Image;
use App\Models\Employee;

class MyHelper {
    static function uploadFile($image, $path="/",$resize=1000) {
        //get Extension
        $img = Image::make($image->getRealPath());
        $ext   = $image->getClientOriginalExtension();
        $doc_name = $path.date('YmdHis').'.'.$ext;


        if ($resize != null) {
            $width  = $img->width();
            if($width > 1000){
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $img->resize($resize, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $resource = $img->stream()->detach();
        $save = \Storage::disk(env('STORAGE'))->put($doc_name, $resource, 'public');

        if($save){
            return [
                "status" => "success",
                "path" => $doc_name,
            ];
        } else {
            return [
                "status" => "fail"
            ];
        }
    }

    static function validationEmployee($employee_id, $company_id) {
        $data = Employee::where("id", $employee_id)->where("company_id", $company_id)->first();

        if($data){
            return true;
        }

        return false;
    }



}
