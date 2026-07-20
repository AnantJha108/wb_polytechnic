<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\NewsEvent as NewsEventModel;
use App\Models\NewsEventLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsEventReview extends Controller
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

    // GET: /admin/dashboard/newsEventReview/index
    public function index()
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $newsItems = NewsEventModel::where('college_id', $collegeId)
            ->where('type', 'news_events')
            ->latest()
            ->get();

        $noticeItems = NewsEventModel::where('college_id', $collegeId)
            ->where('type', 'notice_announcement')
            ->latest()
            ->get();

        return view('backend.admin.newsEvent.reviewNewsEventList', compact('menus', 'newsItems', 'noticeItems'));
    }

    // GET: /admin/dashboard/newsEventReview/show/{id}
    public function show($id)
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $item = NewsEventModel::where('college_id', $collegeId)->with('files')->findOrFail($id);

        $logs = NewsEventLog::where('news_event_id', $item->id)
            ->with('performer')
            ->latest()
            ->get();

        return view('backend.admin.newsEvent.reviewNewsEvent', compact('menus', 'item', 'logs'));
    }

    // POST: /admin/dashboard/newsEventReview/approve/{id}
    public function approve(Request $request, $id)
    {
        $item = NewsEventModel::findOrFail($id);

        if ($item->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded items can be approved.'], 422);
        }

        $item->update([
            'status'        => 'approved',
            'reject_reason' => null,
            'revert_reason' => null,
        ]);

        NewsEventLog::create([
            'news_event_id' => $item->id,
            'action'        => 'approve',
            'reason'        => null,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Item approved and is now live.']);
    }

    // POST: /admin/dashboard/newsEventReview/reject/{id}
    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $item = NewsEventModel::findOrFail($id);

        if ($item->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded items can be rejected.'], 422);
        }

        $item->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
        ]);

        NewsEventLog::create([
            'news_event_id' => $item->id,
            'action'        => 'reject',
            'reason'        => $request->reason,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Item rejected.']);
    }

    // POST: /admin/dashboard/newsEventReview/revert/{id}
    public function revert(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $item = NewsEventModel::findOrFail($id);

        if ($item->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded items can be reverted.'], 422);
        }

        $item->update([
            'status'        => 'reverted',
            'revert_reason' => $request->reason,
        ]);

        NewsEventLog::create([
            'news_event_id' => $item->id,
            'action'        => 'revert',
            'reason'        => $request->reason,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Item reverted back to Operator for edits.']);
    }
}
