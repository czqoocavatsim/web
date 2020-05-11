<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDataExport;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function emailPref()
    {
        return view('me.preferences');
    }
    public function subscribeEmails()
    {
        $user = Auth::user();
        if ($user->gdpr_subscribed_emails == 1) {
            abort(403, 'You need to unsubscribe first.');
        }
        $user->gdpr_subscribed_emails = 1;
        $user->save();
        return redirect()->route('me.preferences')->with('success', 'You are subscribed!');
    }
    public function unsubscribeEmails()
    {
        $user = Auth::user();
        if ($user->gdpr_subscribed_emails == 0) {
            abort(403, 'You need to subscribe first.');
        }
        $user->gdpr_subscribed_emails = 0;
        $user->save();
        return redirect()->route('me.preferences')->with('success', 'You are unsubscribed!');
    }

    public function index()
    {
        return view('dashboard.me.data.index');
    }

    public function exportAllData(Request $request)
    {
        $messages = [
            'email.required' => 'We need your VATSIM email address.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ], $messages);


        if (Auth::user()->email != $request->get('email')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('wrong_email', 'The email you entered does not match your VATSIM email.');
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'exportAll');
        }

        $user = Auth::user();
        ProcessDataExport::dispatch($user);

        $request->session()->flash('exportAll', 'We will email you with your data soon!');
        return redirect()->back();
    }
}
