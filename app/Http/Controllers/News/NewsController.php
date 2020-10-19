<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessAnnouncement;
use App\Jobs\ProcessArticlePublishing;
use App\Models\News\Announcement;
use App\Models\Settings\AuditLogEntry;
use App\Models\News\CarouselItem;
use App\Models\Settings\CoreSettings;
use App\Models\Publications\MeetingMinutes;
use App\Models\News\News;
use App\Models\Publications\UploadedImage;
use App\Models\Users\StaffMember;
use App\Models\Users\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $articles = News::where('certification', false)->get()->sortByDesc('id');
        $announcements = Announcement::all()->sortByDesc('id');
        return view('admin.news.index', compact('articles', 'announcements'));
    }

    public function createArticle()
    {
        $uploadedImgs = UploadedImage::all()->sortByDesc('id');
        $staff = StaffMember::where('user_id', '!=', 1)->get();
        return view('admin.news.articles.create', compact('staff', 'uploadedImgs'));
    }

    public function postArticle(Request $request)
    {
        //Define validator messages
        $messages = [
            'title.required' => 'A title is required.',
            'title.max' => 'A title may not be more than 100 characters long.',
            'image.mimes' => 'We need an image file in the jpg png or gif formats.',
            'content.required' => 'Content is required.',
            'emailOption.required' => 'Please select an email option.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'content' => 'required',
            'emailOption' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createArticleErrors');
        }

        $article = new News();

        //Assign title
        $article->title = $request->get('title');

        //Date for publishing
        $article->published = date('Y-m-d H:i:s');

        //Create slug
        $article->slug = Str::slug($request->get('title').'-'.Carbon::now()->toDateString());

        //Upload image if it exists
        if ($request->file('image')) {
            $path = Storage::disk('digitalocean')->put('staff_uploads/news/' . Carbon::now()->toDateString(), $request->file('image'), 'public');
            $article->image = Storage::url($path);

            //Add to uploaded images
            $uploadedImg = new UploadedImage();
            $uploadedImg->path = Storage::url($path);
            $uploadedImg->user_id = Auth::id();
            $uploadedImg->save();
        }

        //If there is a uplaoded image selected lets put it on there
        if ($request->get('uploadedImage')) {
            $article->image = UploadedImage::whereId($request->get('uploadedImage'))->first()->path;
        }

        //Create a summary if required
        if (!$request->get('summary')) {
            $article->summary = strtok($request->get('content'), '\n');
        } else {
            $article->summary = $request->get('summary');
        }

        //Assign author
        $article->user_id = $request->get('author');
        if ($request->get('showAuthor') == 'on') {
            $article->show_author = true;
        } else {
            $article->show_author = false;
        }

        //Content
        $article->content = $request->get('content');

        //Options
        //Publicly visible
        if ($request->get('articleVisible') == 'on') {
            $article->visible = true;
        } else {
            $article->visible = false;
        }
        //Email level
        switch ($request->get('emailOption')) {
            case 'no':
                $article->email_level = 0;
            break;
            case 'controllers':
                $article->email_level = 1;
            break;
            case 'all':
                $article->email_level = 2;
            break;
            case 'allimportant':
                $article->email_level = 3;
            break;
        }

        //Create and publish if needed
        $article->save();
        if ($article->visible) {
            ProcessArticlePublishing::dispatch($article);
            $request->session()->flash('articleCreated', 'Article created and published!');
        } else {
            $request->session()->flash('artileCreated', 'Article created, but not yet published.');
        }
        return redirect()->route('news.articles.view', $article->slug);
    }

    public function viewArticle($slug)
    {
        $staff = StaffMember::where('user_id', '!=', 1)->get();
        $article = News::where('slug', $slug)->firstOrFail();
        return view('admin.news.articles.view', compact('article', 'staff'));
    }

    public function viewArticlePublic($slug)
    {
        $article = News::where('slug', $slug)->firstOrFail();
        if (!$article->visible) {
            if (Auth::check() && !Auth::user()->permissions > 3) {
                abort(403, 'This article is hidden.');
            }
        }
    return view('news.article', compact('article'));
    }

    public function viewAllPublic()
    {
        $news = News::where('visible', true)->get()->sortByDesc('id');
        return view('news.index', compact('news'));
    }

    public function minutesIndex()
    {
        $minutes = MeetingMinutes::all();

        return view('admin.news.meetingminutes', compact('minutes'));
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

    public function createAnnouncement()
    {
        return view('admin.news.announcements.create');
    }

    public function createAnnouncementPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'title.required' => 'A title is required.',
            'title.max' => 'A title may not be more than 100 characters long.',
            'target_group.required' => 'A target group is required.',
            'content.required' => 'Content is required.',
            'reason_for_sending.required' => 'Please select an email option.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'target_group' => 'required',
            'content' => 'required',
            'reason_for_sending' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createAnnouncementErrors');
        }

        //Create announcement
        $announcement = new Announcement([
            'user_id' => Auth::id(),
            'target_group' => $request->get('target_group'),
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'slug' => Str::slug($request->get('title').'-'.Carbon::now()->toDateString()),
            'reason_for_sending' => $request->get('reason_for_sending'),
            'notes' => $request->get('notes')
        ]);

        $announcement->save();

        //Dispatch the job to send emails
        ProcessAnnouncement::dispatch($announcement);

        //Redirect
        return redirect()->route('news.announcements.view', $announcement->slug);
    }

    public function viewAnnouncement($slug)
    {
        //Find it
        $announcement = Announcement::where('slug', $slug)->firstOrFail();

        //Show it!
        return view('admin.news.announcements.view', compact('announcement'));
    }
}
