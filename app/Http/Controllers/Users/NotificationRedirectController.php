<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\UserNotification;
use Auth;
use Illuminate\Http\Request;

class NotificationRedirectController extends Controller
{
    public function notificationRedirect($id)
    {
        $notification = UserNotification::whereId($id)->firstOrFail();
        if ($notification->user_id != Auth::id()) {
            abort(403, 'You cannot clear a notification that doesnt belong to you.');
        }
        $notificationLink = $notification->link;
        $notification->delete();

        return redirect($notificationLink);
    }

    //TODO FIX THIS BULLSHIT REEEEEEEEEEEEEEEEEEEEEE
    public function clearAll()
    {
        $notifications = Auth::user()->notifications;

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        return back()->with('success', 'Notifications cleared!');
    }
}
