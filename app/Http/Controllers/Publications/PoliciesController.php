<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Settings\AuditLogEntry;
use App\Mail\EmailAnnouncementEmail;
use App\Models\News\News;
use App\Models\Publications\Policy;
use App\Models\Users\User;
use Auth;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;
use Mail;

class PoliciesController extends Controller
{
    public function index()
    {
        if (Auth::check() == false || Auth::user()->permissions < 2) {
            $policies = Policy::where('staff_only', '0')->get();

            return view('policies', compact('policies'));
        } else {
            $policies = Policy::all();

            return view('policies', compact('policies'));
        }
    }

    public function addPolicy(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'details' => 'max:100',
            'link' => 'required',
            'embed' => 'required',
            'staff_only' => 'required',
            'email' => 'required',
        ]);

        if ($request->get('staff_only') == 1 && $request->get('email') != 'none') {
            return redirect()->route('policies')->with('error', 'A private policy cannot be released publicly via email or a news article.')->withInput();
        }

        $policy = new Policy([
            'name' => $request->get('name'),
            'details' => $request->get('details'),
            'link' => $request->get('link'),
            'embed' => $request->get('embed'),
            'staff_only' => $request->get('staff_only'),
            'author' => Auth::user()->id,
            'releaseDate' => date('Y-m-d'),
        ]);

        $policy->save();

        if ($request->get('email') == 'all') {
            $news = new News([
                'title' => 'New Policy: '.$policy->name,
                'content' => "The '".$policy->name."' policy for CZQO has been released. Read it on the policies page.",
                'date' => date('Y-m-d'),
                'type' => 'Email',
                'user_id' => Auth::user()->id,
            ]);
            $news->save();
            $users = User::all();
            foreach ($users as $user) {
                $data = [];
                $data['content'] = $news->content;
                $data['title'] = $news->title;
                $data['fname'] = Auth::user()->fname;
                $data['lname'] = Auth::user()->lname;
                $data['receivingname'] = $user->fname;
                Mail::to($user->email)->send(new EmailAnnouncementEmail($data), function ($message) use ($data) {
                    $message->subject('Gander News: '.$data['title']);
                });
            }
        } elseif ($request->get('email') == 'emailcert') {
            $news = new News([
                'title' => 'New Policy: '.$policy->name,
                'content' => "The '".$policy->name."' policy for CZQO has been released. Read it on the policies page.",
                'date' => date('Y-m-d'),
                'type' => 'CertifiedOnly',
                'user_id' => Auth::user()->id,
            ]);
            $news->save();
            $users = User::all();
            foreach ($users as $user) {
                if ($user->permissions >= 1) {
                    $data = [];
                    $data['content'] = $news->content;
                    $data['title'] = $news->title;
                    $data['fname'] = Auth::user()->fname;
                    $data['lname'] = Auth::user()->lname;
                    $data['receivingname'] = $user->fname;
                    Mail::to($user->email)->send(new EmailAnnouncementEmail($data), function ($message) use ($data) {
                        $message->subject('Gander Controller News: '.$data['title']);
                    });
                }
            }
        } elseif ($request->get('email') == 'newsonly') {
            $news = new News([
                'title' => 'New Policy: '.$policy->name,
                'content' => "The '".$policy->name."' policy for CZQO has been released. Read it on the policies page.",
                'date' => date('Y-m-d'),
                'type' => 'NoEmail',
                'user_id' => Auth::user()->id,
            ]);
            $news->save();
        }
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'CREATE POLICY '.'('.$policy->id.')',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
        ]);
        $entry->save();

        return redirect()->route('policies')->with('success', 'Policy '.$policy->name.' added!');
    }

    public function deletePolicy($id)
    {
        $policy = Policy::where('id', $id)->firstOrFail();
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'DELETE POLICY '.$policy->name.'('.$policy->id.')',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
        ]);
        $entry->save();
        $policy->delete();

        return redirect()->route('policies')->with('success', 'Policy deleted.');
    }
}
