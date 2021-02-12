<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;


class NotificationController extends Controller
{
    public function readNotif($id) {
        $userUnreadNotification = Auth::user()
                            ->unreadNotifications
                            ->where('id', Crypt::decrypt($id))
                            ->first();

        $data = $userUnreadNotification['data']['url'];

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }


        return redirect($data);
    }

    public function readNotifAll() {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->withInput();
    }
}
