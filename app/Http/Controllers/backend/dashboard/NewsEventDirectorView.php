<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\NewsEvent as NewsEventModel;
use App\Models\Menu;
use App\Models\NewsEventLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsEventDirectorView extends Controller
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

    private function guardDirectorOnly()
    {
        $user = Auth::user();
        if (!$user->master || $user->master->name !== 'director') {
            abort(403, 'Only Director can access this page.');
        }
    }

    // GET: /admin/dashboard/newsEventDirectorView/index
    // Step 1: list of all colleges
    public function index()
    {
        $this->guardDirectorOnly();
        $menus = $this->getMenus();

        $colleges = College::orderBy('name')->get();

        return view('backend.admin.newsEvent.directorCollegeList', compact('menus', 'colleges'));
    }

    // GET: /admin/dashboard/newsEventDirectorView/college/{collegeId}
    // Step 2: both tables side-by-side (News col-6, Notice col-6) for that college
    public function college($collegeId)
    {
        $this->guardDirectorOnly();
        $menus   = $this->getMenus();
        $college = College::findOrFail($collegeId);

        $newsItems = NewsEventModel::where('college_id', $college->id)
            ->where('type', 'news_events')
            ->where('status', 'approved')
            ->latest()
            ->get();

        $noticeItems = NewsEventModel::where('college_id', $college->id)
            ->where('type', 'notice_announcement')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('backend.admin.newsEvent.directorCollegeDetail', compact('menus', 'college', 'newsItems', 'noticeItems'));
    }

    // GET: /admin/dashboard/newsEventDirectorView/show/{id}
    // Step 3: full detail of one approved item
    // GET: /admin/dashboard/newsEventDirectorView/show/{id}
    public function show($id)
    {
        $this->guardDirectorOnly();
        $menus = $this->getMenus();

        $item = NewsEventModel::where('status', 'approved')->with('files', 'college')->findOrFail($id);

        $logs = NewsEventLog::where('news_event_id', $item->id)
            ->with('performer')
            ->latest()
            ->get();

        return view('backend.admin.newsEvent.directorShow', compact('menus', 'item', 'logs'));
    }
}
