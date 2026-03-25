<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegePage;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function searchFeedback(Request $request, $slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();

        $template = $college->template;

        $request->validate([
            'ack_number' => 'required'
        ]);

        $feedback = Feedback::where('ack_number', $request->ack_number)
            ->where('college_id', $college->id)
            ->with('messages')
            ->first();

        // 👇 count user messages
        $userCount = 0;

        if ($feedback) {
            $userCount = $feedback->messages->where('sender', 'user')->count();
        }

        $page = CollegePage::where('college_id', $college->id)
            ->where('page', 'contact')
            ->first();

        return view('frontend.' . $template->template_path . '.pages.contact', compact('college', 'feedback', 'page','userCount'));
    }

    // STORE FEEDBACK

    public function store(Request $request, $slug)
    {
        $college = College::where('slug', $slug)->firstOrFail();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $feedback = new Feedback();
        $feedback->college_id = $college->id;
        $feedback->name = $request->name;
        $feedback->email = $request->email;
        $feedback->message = $request->message;
        $feedback->save();

        $ack_no = 'ACK' . date('Ymd') . $feedback->id;
        $feedback->ack_number = $ack_no;
        $feedback->save(); // save again

        return back()->with('success', 'Feedback submitted successfully. Your Ack No: ' . $ack_no);
    }


    public function sendMessage(Request $request, $slug, $ack)
    {
        // Step 1: Get college
        $college = College::where('slug', $slug)->firstOrFail();

        // Step 2: Get feedback using ACK + college
        $feedback = Feedback::where('ack_number', $ack)
            ->where('college_id', $college->id)
            ->firstOrFail();

        // Step 3: Validate message
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Step 4: Count user messages (limit 5)
        $userCount = FeedbackMessage::where('feedback_id', $feedback->id)
            ->where('sender', 'user')
            ->count();

        if ($userCount >= 5) {
            return back()->with('error', 'You have reached maximum reply limit (5)');
        }

        // Step 5: Store user reply
        FeedbackMessage::create([
            'feedback_id' => $feedback->id,
            'sender' => 'user',
            'message' => $request->message
        ]);

        return back()->with('success', 'Message sent successfully');
    }
}
