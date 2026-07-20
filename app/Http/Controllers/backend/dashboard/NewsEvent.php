<?php

namespace App\Http\Controllers\backend\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College as CollegeModel;
use App\Models\NewsEvent as NewsEventModel;
use App\Models\NewsEventFile;
use App\Models\NewsEventLog;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsEvent extends Controller
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

    // Streams the encrypted file straight to disk instead of holding base64 in memory —
    // fixes "Allowed memory size exhausted" with multiple large files in one request.
    private function storeEncryptedFile($file): string
    {
        $raw       = file_get_contents($file->getRealPath());
        $encrypted = Crypt::encrypt($raw);

        $filename = 'news_event_files/' . Str::uuid() . '.enc';
        Storage::disk('local')->put($filename, $encrypted);

        unset($raw, $encrypted);

        return $filename;
    }

    private function fileValidationRules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:news_events,notice_announcement'],
            'description' => ['required', 'string', 'min:10', 'max:10000'],
            'files'       => ['nullable', 'array'],
            'files.*'     => ['file', 'mimes:doc,docx,pdf,ppt,pptx', 'max:5120'], // 5MB PER FILE
        ];
    }

    private function fileValidationMessages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'type.required' => 'Please select a type.',
            'type.in' => 'Invalid type selected.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must contain at least 10 characters.',
            'files.*.mimes' => 'Only Word, PDF, or PowerPoint files are allowed.',
            'files.*.max' => 'Each file must not exceed 5 MB.',
        ];
    }

    // GET: /admin/dashboard/newsEvent/index
    public function index()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $newsItems = NewsEventModel::where('college_id', $college->id)
            ->where('type', 'news_events')
            ->latest()
            ->get();

        $noticeItems = NewsEventModel::where('college_id', $college->id)
            ->where('type', 'notice_announcement')
            ->latest()
            ->get();

        return view('backend.admin.newsEvent.viewNewsEvent', compact('menus', 'newsItems', 'noticeItems'));
    }

    // GET: /admin/dashboard/newsEvent/show/{id}
    public function show($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $item = NewsEventModel::where('college_id', $college->id)->with('files')->findOrFail($id);

        return view('backend.admin.newsEvent.newsEventDetails', compact('menus', 'item'));
    }

    // GET: /admin/dashboard/newsEvent/create
    public function create()
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        return view('backend.admin.newsEvent.addNewsEvent', compact('menus', 'college'));
    }

    // POST: /admin/dashboard/newsEvent/store
    public function store(Request $request)
    {
        $college = $this->getOperatorCollege();

        $validator = Validator::make(
            $request->all(),
            $this->fileValidationRules(),
            $this->fileValidationMessages()
        );

        if ($validator->fails()) {
            // Stash any individually-valid files so they aren't lost on redirect
            $tempFiles = $request->input('temp_files', []);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileCheck = Validator::make(
                        ['file' => $file],
                        ['file' => ['file', 'mimes:doc,docx,pdf,ppt,pptx', 'max:5120']]
                    );

                    if ($fileCheck->passes()) {
                        $tempPath = $file->store('tmp/newsEvent', 'local');
                        $tempFiles[] = [
                            'path' => $tempPath,
                            'name' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ];
                    }
                }
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('files'))
                ->with('temp_files', $tempFiles);
        }

        $item = NewsEventModel::create([
            'college_id'  => $college->id,
            'title'       => $request->title,
            'type'        => $request->type,
            'description' => $request->description,
            'status'      => 'draft',
        ]);

        // Promote any files carried over from a previous failed attempt
        foreach ($request->input('temp_files', []) as $temp) {
            if (Storage::disk('local')->exists($temp['path'])) {
                $raw       = Storage::disk('local')->get($temp['path']);
                $encrypted = Crypt::encrypt($raw);
                $finalPath = 'news_event_files/' . Str::uuid() . '.enc';
                Storage::disk('local')->put($finalPath, $encrypted);
                Storage::disk('local')->delete($temp['path']);

                NewsEventFile::create([
                    'news_event_id'  => $item->id,
                    'original_name'  => $temp['name'],
                    'mime_type'      => $temp['mime'],
                    'encrypted_path' => $finalPath,
                ]);
            }
        }

        // Attach any freshly-selected files from this successful submission
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                NewsEventFile::create([
                    'news_event_id'  => $item->id,
                    'original_name'  => $file->getClientOriginalName(),
                    'mime_type'      => $file->getMimeType(),
                    'encrypted_path' => $this->storeEncryptedFile($file),
                ]);
            }
        }

        return redirect('admin/dashboard/newsEvent/index')->with('success', 'Item saved successfully!');
    }

    // GET: /admin/dashboard/newsEvent/edit/{id}
    public function edit($id)
    {
        $menus   = $this->getMenus();
        $college = $this->getOperatorCollege();

        $item = NewsEventModel::where('college_id', $college->id)->with('files')->findOrFail($id);

        if (!in_array($item->status, ['draft', 'reverted'])) {
            abort(403, 'This item cannot be edited in its current status.');
        }

        return view('backend.admin.newsEvent.editNewsEvent', compact('menus', 'item', 'college'));
    }

    // POST: /admin/dashboard/newsEvent/update/{id}
    public function update(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $item = NewsEventModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($item->status, ['draft', 'reverted'])) {
            abort(403, 'This item cannot be edited in its current status.');
        }

        $validator = Validator::make(
            $request->all(),
            $this->fileValidationRules(),
            $this->fileValidationMessages()
        );

        if ($validator->fails()) {
            $tempFiles = $request->input('temp_files', []);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileCheck = Validator::make(
                        ['file' => $file],
                        ['file' => ['file', 'mimes:doc,docx,pdf,ppt,pptx', 'max:5120']]
                    );

                    if ($fileCheck->passes()) {
                        $tempPath = $file->store('tmp/newsEvent', 'local');
                        $tempFiles[] = [
                            'path' => $tempPath,
                            'name' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ];
                    }
                }
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('files'))
                ->with('temp_files', $tempFiles);
        }

        $item->update([
            'title'       => $request->title,
            'type'        => $request->type,
            'description' => $request->description,
            'status'      => 'draft', // reverted → draft after edit
        ]);

        foreach ($request->input('temp_files', []) as $temp) {
            if (Storage::disk('local')->exists($temp['path'])) {
                $raw       = Storage::disk('local')->get($temp['path']);
                $encrypted = Crypt::encrypt($raw);
                $finalPath = 'news_event_files/' . Str::uuid() . '.enc';
                Storage::disk('local')->put($finalPath, $encrypted);
                Storage::disk('local')->delete($temp['path']);

                NewsEventFile::create([
                    'news_event_id'  => $item->id,
                    'original_name'  => $temp['name'],
                    'mime_type'      => $temp['mime'],
                    'encrypted_path' => $finalPath,
                ]);
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                NewsEventFile::create([
                    'news_event_id'  => $item->id,
                    'original_name'  => $file->getClientOriginalName(),
                    'mime_type'      => $file->getMimeType(),
                    'encrypted_path' => $this->storeEncryptedFile($file),
                ]);
            }
        }

        return redirect('admin/dashboard/newsEvent/index')->with('success', 'Item updated successfully!');
    }

    // DELETE: /admin/dashboard/newsEvent/destroy/{id}
    public function destroy($id)
    {
        $college = $this->getOperatorCollege();

        $item = NewsEventModel::where('college_id', $college->id)->with('files')->findOrFail($id);

        if (!in_array($item->status, ['draft', 'reverted', 'rejected'])) {
            abort(403, 'This item cannot be deleted in its current status.');
        }

        foreach ($item->files as $file) {
            if ($file->encrypted_path) {
                Storage::disk('local')->delete($file->encrypted_path);
            }
        }

        $item->delete(); // cascades to news_event_files via FK onDelete('cascade')

        return redirect('admin/dashboard/newsEvent/index')->with('success', 'Item deleted successfully!');
    }

    // DELETE: /admin/dashboard/newsEvent/deleteFile/{fileId}
    public function deleteFile($fileId)
    {
        $college = $this->getOperatorCollege();

        $file = NewsEventFile::whereHas('newsEvent', function ($q) use ($college) {
            $q->where('college_id', $college->id)
                ->whereIn('status', ['draft', 'reverted']);
        })->findOrFail($fileId);

        if ($file->encrypted_path) {
            Storage::disk('local')->delete($file->encrypted_path);
        }

        $file->delete();

        return back()->with('success', 'File removed.');
    }

    // AJAX POST: /admin/dashboard/newsEvent/forward/{id}
    public function forward(Request $request, $id)
    {
        $college = $this->getOperatorCollege();

        $item = NewsEventModel::where('college_id', $college->id)->findOrFail($id);

        if (!in_array($item->status, ['draft', 'reverted'])) {
            return response()->json(['success' => false, 'message' => 'This item cannot be forwarded right now.'], 422);
        }

        $item->update(['status' => 'forwarded']);

        NewsEventLog::create([
            'news_event_id' => $item->id,
            'action'        => 'forward',
            'reason'        => null,
            'performed_by'  => Auth::id(),
            'ip_address'    => $request->ip(),
        ]);

        return response()->json(['success' => true, 'status' => 'forwarded', 'message' => 'Item forwarded for approval.']);
    }

    // GET: /admin/dashboard/newsEvent/downloadFile/{fileId}
    public function downloadFile($fileId)
    {
        $college = $this->getOperatorCollege();

        $file = NewsEventFile::whereHas('newsEvent', function ($q) use ($college) {
            $q->where('college_id', $college->id);
        })->findOrFail($fileId);

        $encrypted = Storage::disk('local')->get($file->encrypted_path);
        $raw       = Crypt::decrypt($encrypted);

        return response($raw, 200)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_name . '"');
    }
}