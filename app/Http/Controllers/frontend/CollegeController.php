<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegePage;
use App\Models\Feedback;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

        return view(
            'frontend.' . $template->template_path . '.pages.index',
            compact('college', 'page', 'bannerUrl', 'principleImageUrl')
        );
    }

    public function aboutPage($slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $template = $college->template;

        return view(
            'frontend.' . $template->template_path . '.pages.about',
            compact('college')
        );
    }

    public function contactPage(Request $request, $slug)
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
            'frontend.' . $template->template_path . '.pages.contact',
            compact('college', 'feedback', 'userCount')
        );
    }
}
