<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\College;
use App\Models\CollegePage;
use App\Models\Feedback;
use App\Models\NewsEvent;
use App\Models\NewsEventFile;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class CollegeController extends Controller
{
    // Shared helper — decrypt any "mime|base64" encrypted image into a data URI
    private function decryptImage($encrypted)
    {
        if (!$encrypted) {
            return null;
        }

        try {
            $decrypted = Crypt::decryptString($encrypted);
            $parts = explode('|', $decrypted, 2);

            if (count($parts) === 2) {
                [$mimeType, $imageData] = $parts;
                return "data:{$mimeType};base64,{$imageData}";
            }
        } catch (DecryptException $e) {
            return null;
        }

        return null;
    }

    public function index()
    {
        $colleges = College::all();

        foreach ($colleges as $college) {
            $college->logo_url = $this->decryptImage($college->logo);
        }

        return view('index', compact('colleges'));
    }

    // GET: /{slug}  — college home page
    public function openCollege($slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $template = $college->template;

        $college->logo_url = $this->decryptImage($college->logo);

        // Fetch the "home" page content for banner / description / principal message
        $page = CollegePage::where([
            ['college_id', '=', $college->id],
            ['page', '=', 'home'],
            ['status', '=', 'approved']
        ])->first();

        $bannerUrl         = null;
        $principleImageUrl = null;

        if ($page) {
            $bannerUrl         = $this->decryptImage($page->banner);
            $principleImageUrl = $this->decryptImage($page->principle_image);
        }

        $newsItems = NewsEvent::where('college_id', $college->id)
            ->where('type', 'news_events')
            ->where('status', 'approved')
            ->with('files')
            ->latest()
            ->take(3) // limit shown on homepage; adjust as needed
            ->get();

        $noticeItems = NewsEvent::where('college_id', $college->id)
            ->where('type', 'notice_announcement')
            ->where('status', 'approved')
            ->with('files')
            ->latest()
            ->take(3)
            ->get();

        return view(
            'frontend.' . $template->template_path . '.pages.index',
            compact('college', 'page', 'bannerUrl', 'principleImageUrl', 'newsItems', 'noticeItems')
        );
    }

    public function aboutPage($slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $template = $college->template;
        $aboutPage = AboutPage::where('college_id', $college->id)
            ->where('status', 'approved')
            ->latest('id')
            ->first();

        return view(
            'frontend.' . $template->template_path . '.pages.about',
            compact('college', 'aboutPage')
        );
    }

    public function contactPage($slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $template = $college->template;

        return view('frontend.' . $template->template_path . '.pages.contact', compact('college'));
    }

    public function feedbackPage(Request $request, $slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $template = $college->template;

        $feedback = Feedback::where('ack_number', $request->ack_no)
            ->where('college_id', $college->id)
            ->with('messages')
            ->first();

        $userCount = 0;

        if ($feedback) {
            $userCount = $feedback->messages->where('sender', 'user')->count();
        }

        return view(
            'frontend.' . $template->template_path . '.pages.feedback',
            compact('college', 'feedback', 'userCount')
        );
    }

    public function downloadNewsEventFile($fileId)
    {
        $file = NewsEventFile::whereHas('newsEvent', function ($q) {
            $q->where('status', 'approved'); // only allow downloading files from approved items
        })->findOrFail($fileId);

        $encrypted = Storage::disk('local')->get($file->encrypted_path);
        $raw = Crypt::decrypt($encrypted);

        return response($raw, 200)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"');
    }
}
