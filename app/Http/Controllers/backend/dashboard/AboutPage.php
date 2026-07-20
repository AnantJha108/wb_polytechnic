<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\AboutPage as AboutPageModel;
use App\Models\AboutPageLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AboutPage extends Controller
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

    private function getOperatorCollege()
    {
        $user = Auth::user();
        if (!$user->college_id) abort(403, 'No college is assigned to your account.');
        return CollegeModel::findOrFail($user->college_id);
    }

    // GET: /admin/dashboard/aboutPage/index
    // $current = the active/latest record (drives Add/Edit/View form)
    // $archived = every older record (view + delete only, always superseded rejected ones)
    public function index()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $pages = AboutPageModel::where('college_id', $college->id)
            ->orderByDesc('id')
            ->get();

        $current  = $pages->first();
        $archived = $pages->slice(1);

        return view('backend.admin.aboutPage.manageAboutPage', compact('menus', 'college', 'current', 'archived'));
    }

    // POST: /admin/dashboard/aboutPage/store
    public function store(Request $request)
    {
        $college = $this->getOperatorCollege();

        $request->validate(
            [
                'description' => ['required', 'string', 'min:20', 'max:10000'],
            ],
            [
                'description.required' => 'Description is required.',
                'description.min' => 'Description must contain at least 20 characters.',
                'description.max' => 'Description cannot exceed 10000 characters.',
            ]
        );

        $latest = AboutPageModel::where('college_id', $college->id)->latest('id')->first();

        // Only allow a new record if none exists yet, or the current one was rejected
        if ($latest && $latest->status !== 'rejected') {
            return redirect('admin/dashboard/aboutPage/index')
                ->with('error', 'An About page already exists. Please update it instead of adding a new one.');
        }

        AboutPageModel::create([
            'college_id'  => $college->id,
            'description' => $request->description,
            'status'      => 'draft',
        ]);

        return redirect('admin/dashboard/aboutPage/index')->with('success', 'About page saved successfully!');
    }

    // POST: /admin/dashboard/aboutPage/update/{id}
    public function update(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $page = AboutPageModel::where('college_id', $college->id)->findOrFail($id);

        // Only the CURRENT (latest) record can be edited, and never while forwarded or rejected
        $latestId = AboutPageModel::where('college_id', $college->id)->latest('id')->value('id');

        if ($page->id !== $latestId) {
            abort(403, 'Archived pages cannot be edited.');
        }

        if (!in_array($page->status, ['draft', 'reverted', 'approved'])) {
            abort(403, 'This page cannot be edited in its current status.');
        }

        $request->validate(
            [
                'description' => ['required', 'string', 'min:20', 'max:10000'],
            ],
            [
                'description.required' => 'Description is required.',
                'description.min' => 'Description must contain at least 20 characters.',
                'description.max' => 'Description cannot exceed 10000 characters.',
            ]
        );

        $page->update([
            'description'   => $request->description,
            'status'        => 'draft', // any edit — including editing an approved page — resets to draft
            'reject_reason' => null,
            'revert_reason' => null,
        ]);

        return redirect('admin/dashboard/aboutPage/index')
            ->with('success', 'About page updated successfully! It has been reset to Draft — forward it again for approval.');
    }

    // DELETE: /admin/dashboard/aboutPage/destroy/{id}
    public function destroy($id)
    {
        $college = $this->getOperatorCollege();

        $page = AboutPageModel::where('college_id', $college->id)->findOrFail($id);

        $latestId = AboutPageModel::where('college_id', $college->id)->latest('id')->value('id');
        $isArchived = $page->id !== $latestId;

        // Archived records: always deletable.
        // Current record: only deletable in draft, reverted, or rejected — matches College page's delete logic.
        if (!$isArchived && !in_array($page->status, ['draft', 'reverted', 'rejected'])) {
            abort(403, 'This page cannot be deleted in its current status.');
        }

        $page->delete();

        return redirect('admin/dashboard/aboutPage/index')->with('success', 'About page deleted successfully!');
    }

    // AJAX POST: /admin/dashboard/aboutPage/forward/{id}
    public function forward(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $page = AboutPageModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($page->status, ['draft', 'reverted'])) {
            return response()->json(['success' => false, 'message' => 'This page cannot be forwarded right now.'], 422);
        }

        $page->update(['status' => 'forwarded']);

        AboutPageLog::create([
            'about_page_id' => $page->id,
            'action'        => 'forward',
            'reason'        => null,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'forwarded', 'message' => 'Page forwarded for approval.']);
    }
}