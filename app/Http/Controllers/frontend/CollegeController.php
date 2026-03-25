<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegePage;
use App\Models\Feedback;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index()
    {

        $colleges = College::all();

        return view('index', compact('colleges'));
    }


    public function openCollege($slug)
    {

        $college = College::where('slug', $slug)->firstOrFail();

        $template = $college->template;

        return view('frontend.' . $template->template_path . '.pages.index', compact('college'));
    }

    public function aboutPage($slug)
    {

        $college = College::where('slug', $slug)->firstOrFail();

        $template = $college->template;

        $page = CollegePage::where('college_id', $college->id)
            ->where('page', 'about')
            ->first();

        return view(
            'frontend.' . $template->template_path . '.pages.about',
            compact('college','page')
        );
    }


    public function contactPage(Request $request,$slug)
    {

        $college = College::where('slug', $slug)->firstOrFail();

        $template = $college->template;

        $page = CollegePage::where('college_id', $college->id)
            ->where('page', 'contact')
            ->first();

        $feedback = Feedback::where('ack_number', $request->ack_no)
            ->where('college_id', $college->id)
            ->with('messages')
            ->first();
        
        // 👇 count user messages
        $userCount = 0;

        if ($feedback) {
            $userCount = $feedback->messages->where('sender', 'user')->count();
        }

        return view(
            'frontend.' . $template->template_path . '.pages.contact',
            compact('college','page','feedback','userCount')
        );
    }
}
