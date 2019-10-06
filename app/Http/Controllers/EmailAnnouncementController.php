<?php

namespace App\Http\Controllers;

use App\Mail\EmailAnnouncementEmail;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class EmailAnnouncementController extends Controller
{
    public function create()
    {
        return view('/dashboard/news/announcement/emailannouncement.create');
    }

    public function submitted()
    {
        return view('/dashboard/news/announcement/emailannouncement.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'msg' => 'required',
        ]);

        $users = User::all();

        foreach ($users as $user) {
            $data = [];
            $data['content'] = $request->get('msg');
            $data['title'] = 'FIR Announcement';
            $data['fname'] = Auth::user()->fname;
            $data['lname'] = Auth::user()->lname;
            $data['receivingname'] = $user->fname;
            Mail::to($user->email)->send(new EmailAnnouncementEmail($data));
        }

        return redirect('/dashboard/news');
    }
}
