<?php

namespace App\Http\Controllers;

use App\Models\Events\CtpSignUp;
use App\Mail\CtpSignUpEmail;
use App\Models\AtcTraining\RosterMember;
use App\Models\Publications\AtcResource;
use App\Models\Tickets\Ticket;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $certification = null;
        $active = null;
        $potentialRosterMember = RosterMember::where('user_id', $user->id)->first();
        if ($potentialRosterMember === null) {
            $certification = 'not_certified';
            $active = 2;
        } else {
            $certification = $potentialRosterMember->status;
            $active = $potentialRosterMember->active;
        }
        $openTickets = Ticket::where('user_id', $user->id)->where('status', 0)->get();

        $atcResources = AtcResource::all()->sortBy('title');

        if ($user->preferences->enable_beta_features) {
            return view('dashboard.indexnew', compact('openTickets', 'certification', 'active', 'atcResources'));
        } else {
            return view('dashboard.index', compact('openTickets', 'certification', 'active', 'atcResources'));
        }
    }

    public function ctpSignUp(Request $request)
    {
        $availability = $request->get('availability');
        $times = $request->get('times');

        $signup = new CtpSignUp();
        $signup->user_id = Auth::id();
        $signup->availability = $availability;
        if ($times == null || $times == '') {
            $signup->times = 'None specified';
        } else {
            $signup->times = $times;
        }
        $signup->submitted_at = date('Y-m-d H:i:s');
        $signup->save();
        $hookObject = json_encode([
            /*
             * The general "message" shown above your embeds
             */
            'content' => 'Wow! Someone has signed up to control CTP! View all signups here: https://ganderoceanic.com/api/ctpsignups (you need to be signed in)',
            /*
             * The username shown in the message
             */
            'username' => 'Gander Oceanic Core',
            /*
             * The image location for the senders image
             */
            'avatar_url' => 'https://cdn.discordapp.com/avatars/482857020496805898/e1986c594dde3c0feec30e084a41a3e3.png?size=256',
            /*
             * Whether or not to read the message in Text-to-speech
             */
            'tts' => false,
            /*
             * File contents to send to upload a file
             */
            // "file" => "",
            /*
             * An array of Embeds
             */
            'embeds' => [
                [
                    // Set the title for your embed
                    'title' => Auth::user()->fullName('FLC'),

                    // The type of your embed, will ALWAYS be "rich"
                    'type' => 'rich',

                    // A description for your embed
                    'description' => 'Available: '.$signup->availability.' Times: '.$signup->times,

                    // The URL of where your title will be a link to
                    'url' => route('users.viewprofile', Auth::id()),

                    'timestamp' => date('Y-m-d H:i:s'),

                    // The integer color to be used on the left side of the embed
                    'color' => hexdec('FFFFFF'),

                    'footer' => [
                        'text' => 'Created '.date('Y-m-d H:i:s'),
                    ],

                    /*"fields" => [
                        [
                            "name" => "Data A",
                            "value" => "Value A",
                            "inline" => false
                        ],
                    ]*/
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => config('discord.staff_webhook'),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                'Length' => strlen($hookObject),
                'Content-Type' => 'application/json',
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        Mail::to('liesel.downes@icloud.com')->send(new CtpSignUpEmail($signup));

        return redirect()->back()->with('success', 'Signed up!');
    }
}
