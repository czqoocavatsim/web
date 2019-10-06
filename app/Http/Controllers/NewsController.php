<?php

namespace App\Http\Controllers;

use App\AuditLogEntry;
use App\CarouselItem;
use App\CoreSettings;
use App\DiscordWebhook;
use App\Mail\EmailAnnouncementEmail;
use App\MeetingMinutes;
use App\News;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function home()
    {
        //$news = News::all()->sortByDesc('id');
        $news = News::where('type', '!=', 'Certification')->get()->sortByDesc('id')->take('3');
        $promotions = News::where('type', 'Certification')->get()->sortByDesc('id')->take('5');
        $carouselItems = CarouselItem::all();

        return view('dashboard.news.home', compact('news', 'promotions', 'carouselItems'));
    }

    public function create()
    {
        return view('dashboard.news.create');
    }

    public function submitted()
    {
        return view('dashboard.feedback.submitted');
    }

    public function store(Request $request)
    {
        $validateddata = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $news = new News([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'date' => date('Y-m-d'),
            'type' => $request->get('type'),
            'user_id' => Auth::user()->id,
            'slug' => strtolower(Str::slug($request->get('title'))),
        ]);

        $news->save();

        if ($news->type == 'Email') {
            $users = User::all();
            foreach ($users as $user) {
                if ($user->gdpr_subscribed_emails == 0) {
                    continue;
                }
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
        } elseif ($news->type == 'CertifiedOnly') {
            $users = User::all();
            foreach ($users as $user) {
                if ($user->gdpr_subscribed_emails == 0) {
                    continue;
                }
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
        }

        createNewsMessage($news);

        return redirect()->route('news.home')->with('success', 'Article '.$news->title.' saved and published!');
    }

    public function setSiteBanner(Request $request)
    {
        $validateddata = $request->validate([
            'content' => 'required',
            'url' => 'required',
            'mode' => 'required',
        ]);

        $coreSettings = CoreSettings::where('id', 1)->firstOrFail();
        $coreSettings->banner = $request->get('content');
        $coreSettings->bannerLink = $request->get('url');
        $coreSettings->bannerMode = $request->get('mode');
        $coreSettings->save();

        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'ADJUST SITE BANNER',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
        ]);
        $entry->save();

        return redirect()->route('news.home')->with('success', 'Banner set.');
    }

    public function removeSiteBanner()
    {
        $coreSettings = CoreSettings::where('id', 1)->firstOrFail();
        $coreSettings->banner = '';
        $coreSettings->save();

        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'REMOVE SITE BANNER',
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
        ]);
        $entry->save();

        return redirect()->route('news.home')->with('success', 'Banner removed.');
    }

    public function deleteAllArticles()
    {
        News::truncate();

        return redirect()->route('news.home')->with('success', 'All articles deleted!');
    }

    public function deleteArticle($id)
    {
        $article = News::where('id', $id)->firstOrFail();
        $entry = new AuditLogEntry([
            'user_id' => Auth::user()->id,
            'affected_id' => 1,
            'action' => 'DELETE ARTICLE '.$article->id,
            'time' => date('Y-m-d H:i:s'),
            'private' => 0,
        ]);
        $entry->save();
        $article->delete();

        return redirect()->route('news.home')->with('success', 'Article '.$article->title.' deleted!');
    }

    public function viewArticle($id)
    {
        $article = News::where('id', $id)->firstOrFail();

        return view('dashboard.news.view', compact('article'));
    }

    public function archiveArticle($id, $mode)
    {
        $article = News::where('id', $id)->firstOrFail();
        if ($mode == 'true') {
            $article->archived = 1;
            $article->save();
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => 1,
                'action' => 'ARCHIVE ARTICLE '.$article->id,
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();

            return view('dashboard.news.view', compact('article'))->with('sucess', 'Article '.$article->title.' archived.');
        } elseif ($mode == 'false') {
            $article->archived = 0;
            $article->save();
            $entry = new AuditLogEntry([
                'user_id' => Auth::user()->id,
                'affected_id' => 1,
                'action' => 'UNARCHIVE ARTICLE '.$article->id,
                'time' => date('Y-m-d H:i:s'),
                'private' => 0,
            ]);
            $entry->save();

            return view('dashboard.news.view', compact('article'))->with('sucess', 'Article '.$article->title.' unarchived.');
        } else {
            abort(400, 'Bad URL syntax');
        }
    }

    public function viewPublicArticleInt($id)
    {
        $article = News::where('id', $id)->firstOrFail();
        if ($article->archived == 1) {
            abort(403);
        }

        return view('publicarticle', compact('article'));
    }

    public function viewPublicArticle($slug)
    {
        $article = News::where('slug', $slug)->firstOrFail();
        if ($article->archived == 1) {
            abort(403);
        }

        return view('publicarticle', compact('article'));
    }

    public function viewPublicAll()
    {
        $news = News::all()->sortByDesc('id');

        return view('publicnews', compact('news'));
    }

    public function addCarousel(Request $request)
    {
        $validateddata = $request->validate([
            'image_url' => 'required|url',
            'caption' => 'nullable|max:50',
            'caption_url' => 'nullable|url',
        ]);

        $item = new CarouselItem([
            'image_url' => $request->get('image_url'),
            'caption' => $request->get('caption'),
            'caption_url' => $request->get('caption_url'),
        ]);

        $item->save();

        return redirect()->route('news.home')->with('success', 'Carousel item added!');
    }

    public function deleteCarousel($id)
    {
        $item = CarouselItem::where('id', $id)->firstOrFail();
        $item->delete();

        return redirect()->route('news.home')->with('success', 'Carousel item deleted!');
    }

    public function minutesIndex()
    {
        $minutes = MeetingMinutes::all();

        return view('dashboard.news.meetingminutes', compact('minutes'));
    }

    public function minutesDelete($id)
    {
        $minutes = MeetingMinutes::whereId($id)->firstOrFail();
        AuditLogEntry::insert(Auth::user(), 'Deleted meeting minutes '.$minutes->title, User::find(1), 0);
        $minutes->delete();

        return redirect()->back()->with('info', 'Deleted item');
    }

    public function minutesUpload(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'file' => 'required',
        ]);

        $file = $request->file('file');

        $fileName = $file->getClientOriginalName();

        Storage::disk('local')->putFileAs(
            'public/files/minutes', $file, $fileName
        );

        $minutes = new MeetingMinutes([
            'user_id' => Auth::id(),
            'title' => $request->get('title'),
            'link' => Storage::url('public/files/minutes/'.$fileName),
        ]);

        $minutes->save();

        AuditLogEntry::insert(Auth::user(), 'Uploaded meeting minutes '.$minutes->title, User::find(1), 0);

        return redirect()->back()->with('success', 'Minutes uploaded!');
    }
}
