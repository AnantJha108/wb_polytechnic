<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\AboutPage as AboutPageModel;
use App\Models\AboutPageLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AboutPageReview extends Controller
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

    // (matches your existing "collegepagestatus" pattern — shows current page + activity log for reviewer's college)
    // GET: /admin/dashboard/aboutPageReview/index
    public function index()
    {
        $menus     = $this->getMenus();
        $collegeId = Auth::user()->college_id;

        $page = AboutPageModel::where('college_id', $collegeId)->latest()->first();

        $logs = collect();
        if ($page) {
            $logs = AboutPageLog::where('about_page_id', $page->id)
                ->with('performer')   // must exactly match method name `performer()`
                ->latest()
                ->get();
        }

        return view('backend.admin.aboutPage.reviewAboutPage', compact('menus', 'page', 'logs'));
    }

    // POST: /admin/dashboard/aboutPageReview/approve/{id}
    public function approve(Request $request, $id)
    {
        $page = AboutPageModel::findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be approved.'], 422);
        }

        $page->update([
            'status'         => 'approved',
            'reject_reason'  => null,
            'revert_reason'  => null,
        ]);

        AboutPageLog::create([
            'about_page_id' => $page->id,
            'action'        => 'approve',
            'reason'        => null,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Page approved and is now live.']);
    }

    // POST: /admin/dashboard/aboutPageReview/reject/{id}
    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $page = AboutPageModel::findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be rejected.'], 422);
        }

        $page->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
        ]);

        AboutPageLog::create([
            'about_page_id' => $page->id,
            'action'        => 'reject',
            'reason'        => $request->reason,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Page rejected.']);
    }

    // POST: /admin/dashboard/aboutPageReview/revert/{id}
    public function revert(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|min:5|max:1000']);

        $page = AboutPageModel::findOrFail($id);

        if ($page->status !== 'forwarded') {
            return response()->json(['success' => false, 'message' => 'Only forwarded pages can be reverted.'], 422);
        }

        $page->update([
            'status'        => 'reverted',
            'revert_reason' => $request->reason,
        ]);

        AboutPageLog::create([
            'about_page_id' => $page->id,
            'action'        => 'revert',
            'reason'        => $request->reason,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'message' => 'Page reverted back to Operator for edits.']);
    }
}
