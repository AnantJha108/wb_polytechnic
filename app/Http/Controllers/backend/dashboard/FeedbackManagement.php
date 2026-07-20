<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackManagement extends Controller
{
    public function getMenus()
    {
        $user = Auth::user();
        if (!$user->master_id) return collect();

        $menuIds = DB::table('menu_user_maps')->where('master_id', $user->master_id)->pluck('menu_id')->toArray();
        if (empty($menuIds)) return collect();

        $childMenus = Menu::whereIn('id', $menuIds)->where('menu_id', '!=', 0)->get()->groupBy('menu_id');

        return Menu::where('menu_id', 0)->get()
            ->filter(fn($parent) => isset($childMenus[$parent->id]))
            ->map(function ($parent) use ($childMenus) {
                $parent->children = $childMenus[$parent->id];
                return $parent;
            });
    }

    private function isDirector()
    {
        $user = Auth::user();
        return $user->master && $user->master->name === 'director';
    }

    // GET: /admin/dashboard/feedbackManagement/index
    // Principal → directly lists their college's feedback
    // Director → lists all colleges first (drill-down)
    public function index()
    {
        $menus = $this->getMenus();

        if ($this->isDirector()) {
            $colleges = College::orderBy('name')->get();
            return view('backend.admin.feedback.collegeList', compact('menus', 'colleges'));
        }

        $user = Auth::user();
        if (!$user->college_id) abort(403, 'No college is assigned to your account.');

        $feedbacks = Feedback::where('college_id', $user->college_id)
            ->withCount(['messages as user_reply_count' => function ($q) {
                $q->where('sender', 'user');
            }])
            ->latest()
            ->get();

        $college = College::findOrFail($user->college_id);

        return view('backend.admin.feedback.feedbackList', compact('menus', 'feedbacks', 'college'));
    }

    // GET: /admin/dashboard/feedbackManagement/college/{collegeId}
    // Director only — list feedback for one specific college
    public function college($collegeId)
    {
        if (!$this->isDirector()) {
            abort(403, 'Only Director can browse colleges this way.');
        }

        $menus   = $this->getMenus();
        $college = College::findOrFail($collegeId);

        $feedbacks = Feedback::where('college_id', $college->id)
            ->withCount(['messages as user_reply_count' => function ($q) {
                $q->where('sender', 'user');
            }])
            ->latest()
            ->get();

        return view('backend.admin.feedback.feedbackList', compact('menus', 'feedbacks', 'college'));
    }

    // GET: /admin/dashboard/feedbackManagement/show/{id}
    // Chat view — shared by Principal and Director
    public function show($id)
    {
        $menus = $this->getMenus();
        $user  = Auth::user();

        $feedback = Feedback::with('messages.performer')->findOrFail($id);
        $college  = College::findOrFail($feedback->college_id);

        if (!$this->isDirector() && $user->college_id != $feedback->college_id) {
            abort(403, 'You are not authorized to view this feedback.');
        }

        $adminReplyCount = $feedback->messages->where('sender', 'admin')->count();

        return view('backend.admin.feedback.feedbackChat', compact('menus', 'feedback', 'college', 'adminReplyCount'));
    }

    // POST: /admin/dashboard/feedbackManagement/reply/{id}
    public function reply(Request $request, $id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        if (!$this->isDirector() && $user->college_id != $feedback->college_id) {
            abort(403, 'You are not authorized to reply to this feedback.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $adminReplyCount = FeedbackMessage::where('feedback_id', $feedback->id)
            ->where('sender', 'admin')
            ->count();

        if ($adminReplyCount >= 5) {
            return back()->with('error', 'You have reached the maximum reply limit (5).');
        }

        FeedbackMessage::create([
            'feedback_id'  => $feedback->id,
            'sender'       => 'admin',
            'performed_by' => $user->id,
            'message'      => $request->message,
        ]);

        return redirect('admin/dashboard/feedbackManagement/show/' . $feedback->id)
            ->with('success', 'Reply sent successfully.');
    }
}
