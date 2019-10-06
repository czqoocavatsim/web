<?php

namespace App\Http\Controllers;

use App\CoreSettings;
use App\Mail\GDPRDataEmail;
use App\Mail\GDPRRequestEmail;
use App\Ticket;
use App\User;
use App\UserNote;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;
use PDF;

class GDPRController extends Controller
{
    public function create()
    {
        return view('dashboard/data.create');
    }

    public function submitted()
    {
        return view('dashboard/data.submitted');
    }

    public function emailPref()
    {
        return view('dashboard.emailpref');
    }

    public function subscribeEmails()
    {
        $user = Auth::user();
        if ($user->gdpr_subscribed_emails == 1) {
            abort(403, 'You need to unsubscribe first.');
        }
        $user->gdpr_subscribed_emails = 1;
        $user->save();

        return redirect()->route('dashboard.emailpref')->with('success', 'You are subscribed!');
    }

    public function unsubscribeEmails()
    {
        $user = Auth::user();
        if ($user->gdpr_subscribed_emails == 0) {
            abort(403, 'You need to subscribe first.');
        }
        $user->gdpr_subscribed_emails = 0;
        $user->save();

        return redirect()->route('dashboard.emailpref')->with('success', 'You are unsubscribed!');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
        ]);

        $data = [];
        $data['fname'] = Auth::user()->fname;
        $data['lname'] = Auth::user()->lname;
        $content = "Gander Oceanic FIR User Data \n";
        $content .= 'For User '.Auth::user()->id.' as of '.date('Y-m-d H:i:i')."\n";
        $content .= "GENERAL USER DATA \n -------------------------------------\n";
        $content .= Auth::user()->toJson(JSON_PRETTY_PRINT);
        $content .= "\n CONTROLLER APPLICATIONS \n -------------------------------------\n";
        $content .= Auth::user()->applications->toJson(JSON_PRETTY_PRINT);
        $content .= "\n BASE TICKETS \n -------------------------------------\n";
        $content .= Ticket::where('user_id', Auth::user()->id)->get()->toJson(JSON_PRETTY_PRINT);
        $data['json'] = $content;

        Mail::to($request->get('email'))->send(new GDPRDataEmail($data));

        return redirect()->route('data.submitted');
    }

    public function removeData()
    {
        if (Auth::user()->permissions >= 5) {
            return view('dashboard.data.remove', ['canremove' => 'false']);
        }

        return view('dashboard.data.remove', ['canremove' => 'true']);
    }

    public function downloadData()
    {
        //Get basic user data
        $basicData = Auth::user();
        //Get user notes
        $userNotes = UserNote::where('user_id', Auth::id())->get();
        //Get controller applications
        $applications = Auth::user()->applications;
        //Get student profile
        $studentProfile = Auth::user()->studentProfile;
        //Get instructor profile
        $instructorProfile = Auth::user()->instructorProfile;
        //Get all tickets created
        $tickets = Ticket::where('user_id', Auth::id())->get();
        //return view('dashboard.data.datadownloadpdf', compact('basicData', 'userNotes', 'applications', 'studentProfile', 'instructorProfile', 'tickets'));
        $pdf = PDF::loadView('dashboard.data.datadownloadpdf', compact('basicData', 'userNotes', 'applications', 'studentProfile', 'instructorProfile', 'tickets'));

        return $pdf->download(Auth::id().' '.Carbon::now().'.pdf');
    }

    ///
    /// Remove user data under GDPR notification to director and webmaster
    public function removeDataStore(Request $request)
    {
        //Validate form
        $vaildatedData = $request->validate([
            'email' => 'required',
            'deleteMethod' => 'required',
        ]);

        //Get current user
        $user = Auth::user();

        //Verify email address entered
        $email = $request->get('email');
        if (strtolower($user->email) != strtolower($email)) {
            return redirect('dashboard/data/remove')->with('error', 'Incorrect email provided. Please try again with your CERT email')->withInput();
        }

        //Get delete method selected
        $method = $request->get('deleteMethod');

        $data = [];
        $data['requestUser'] = $user->id;
        $data['method'] = $method;
        Mail::to(CoreSettings::whereId(1)->firstOrFail()->emailwebmaster)->send(new GDPRRequestEmail($data));

        return redirect()->route('dashboard.index')->with('success', 'Request submitted. You will remain logged in until the data removal has been processed by our staff.');
    }
}
