<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProblemTag;
use Illuminate\Http\Request;

class ProblemTagController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        if ($perPage < 5) {
            $perPage = 5;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $tags = ProblemTag::query()
            ->orderByDesc('Tag_ID')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.problemtags.index', [
            'tagCount' => $tags->total(),
            'tags' => $tags,
            'perPage' => $perPage,
            'tag' => new ProblemTag(),
        ]);
    }

    public function create()
    {
        return view('admin.problemtags.form', [
            'tag' => new ProblemTag(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tag_name' => ['required', 'string', 'max:100'],
            'tag_color' => ['nullable', 'string', 'max:20'],
            'tag_detail' => ['nullable', 'string'],
        ]);

        ProblemTag::query()->create($data);

        return redirect()
            ->route('admin.problemtags.index')
            ->with('success', 'ProblemTag created');
    }

    public function edit(ProblemTag $problemTag)
    {
        return view('admin.problemtags.form', [
            'tag' => $problemTag,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, ProblemTag $problemTag)
    {
        $data = $request->validate([
            'tag_name' => ['required', 'string', 'max:100'],
            'tag_color' => ['nullable', 'string', 'max:20'],
            'tag_detail' => ['nullable', 'string'],
        ]);

        $problemTag->update($data);

        return redirect()
            ->route('admin.problemtags.index')
            ->with('success', 'ProblemTag updated');
    }

    public function destroy(ProblemTag $problemTag)
    {
        $problemTag->delete();

        return redirect()
            ->route('admin.problemtags.index')
            ->with('success', 'ProblemTag deleted');
    }
}


