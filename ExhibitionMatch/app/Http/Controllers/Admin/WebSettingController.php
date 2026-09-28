<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebSettingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));

        $sets = WebSetting::query()
            ->orderByDesc('Web_ID')
            ->paginate($perPage)
            ->withQueryString();

        $setCount = WebSetting::query()->count();
        $viewId = $request->integer('view');
        $viewSet = null;

        if ($viewId) {
            $viewSet = WebSetting::query()->find($viewId);
        }

        return view('admin.websettings.index', [
            'sets' => $sets,
            'perPage' => $perPage,
            'setCount' => $setCount,
            'viewId' => $viewId,
            'viewSet' => $viewSet,
        ]);
    }

    public function create()
    {
        return view('admin.websettings.form', [
            'set' => new WebSetting(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        $data = array_merge($data, $this->storeUploads($request));

        WebSetting::query()->create($data);

        return redirect()
            ->route('admin.websettings.index')
            ->with('success', 'WebSetting created');
    }

    public function edit(WebSetting $webSetting)
    {
        return view('admin.websettings.form', [
            'set' => $webSetting,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, WebSetting $webSetting)
    {
        $data = $this->validatePayload($request);

        $uploads = $this->storeUploads($request);
        foreach ($uploads as $key => $path) {
            $this->deleteIfPresent($webSetting->{$key});
        }

        $webSetting->update(array_merge($data, $uploads));

        return redirect()
            ->route('admin.websettings.index')
            ->with('success', 'WebSetting updated');
    }

    public function destroy(WebSetting $webSetting)
    {
        $this->deleteIfPresent($webSetting->landing_image_path);
        $this->deleteIfPresent($webSetting->seminar_map_image_path);

        $webSetting->delete();

        return redirect()
            ->route('admin.websettings.index')
            ->with('success', 'WebSetting deleted');
    }

    public function preview(WebSetting $webSetting)
    {
        $landingImageUrl = null;
        if ($webSetting->landing_image_path) {
            $landingImageUrl = asset('storage/' . ltrim($webSetting->landing_image_path, '/'));
        }

        $mapImageUrl = null;
        if ($webSetting->seminar_map_image_path) {
            $mapImageUrl = asset('storage/' . ltrim($webSetting->seminar_map_image_path, '/'));
        }

        return view('admin.websettings.preview', [
            'set' => $webSetting,
            'landingImageUrl' => $landingImageUrl,
            'mapImageUrl' => $mapImageUrl,
        ]);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'web_title' => ['nullable', 'string', 'max:150'],
            'web_detail' => ['nullable', 'string'],
            'landing_image' => ['nullable', 'image', 'max:4096'],
            'seminar_map_image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function storeUploads(Request $request): array
    {
        $out = [];

        if ($request->hasFile('landing_image')) {
            $out['landing_image_path'] = $request->file('landing_image')->store('websettings', 'public');
        }

        if ($request->hasFile('seminar_map_image')) {
            $out['seminar_map_image_path'] = $request->file('seminar_map_image')->store('websettings', 'public');
        }

        return $out;
    }

    private function deleteIfPresent(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}


