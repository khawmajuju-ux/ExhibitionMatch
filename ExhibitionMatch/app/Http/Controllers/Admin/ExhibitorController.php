<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\ProblemTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExhibitorController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 5) {
            $perPage = 5;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $exhibitors = Exhibitor::query()
            ->with('problemTag')
            ->orderByDesc('Ex_ID')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.exhibitors.index', [
            'exhibitorCount' => $exhibitors->total(),
            'exhibitors' => $exhibitors,
            'perPage' => $perPage,
        ]);
    }

    public function show(Exhibitor $exhibitor)
    {
        $exhibitor->load('problemTag');

        return view('admin.users.exhibitors.show', [
            'exhibitor' => $exhibitor,
        ]);
    }

    public function create()
    {
        $tags = ProblemTag::query()->orderBy('tag_name')->get();

        return view('admin.users.exhibitors.form', [
            'exhibitor' => new Exhibitor(),
            'tags' => $tags,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ex_companyName' => ['required', 'string', 'max:150'],
            'ex_companyEmail' => ['nullable', 'string', 'max:150'],
            'ex_companyPhone' => ['nullable', 'string', 'max:30'],
            'Tag_id' => ['nullable', 'integer', 'exists:problemtags,Tag_ID'],
            'ex_website' => ['nullable', 'string', 'max:255'],
            'ex_logo' => ['nullable', 'string', 'max:255'],
        ]);

        Exhibitor::query()->create($data);

        return redirect()
            ->route('admin.exhibitors.index')
            ->with('success', 'Exhibitor created');
    }

    public function edit(Exhibitor $exhibitor)
    {
        $tags = ProblemTag::query()->orderBy('tag_name')->get();

        return view('admin.users.exhibitors.form', [
            'exhibitor' => $exhibitor,
            'tags' => $tags,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Exhibitor $exhibitor)
    {
        $data = $request->validate([
            'ex_companyName' => ['required', 'string', 'max:150'],
            'ex_companyEmail' => ['nullable', 'string', 'max:150'],
            'ex_companyPhone' => ['nullable', 'string', 'max:30'],
            'Tag_id' => ['nullable', 'integer', 'exists:problemtags,Tag_ID'],
            'ex_website' => ['nullable', 'string', 'max:255'],
            'ex_logo' => ['nullable', 'string', 'max:255'],
        ]);

        $exhibitor->update($data);

        return redirect()
            ->route('admin.exhibitors.index')
            ->with('success', 'Exhibitor updated');
    }

    public function destroy(Exhibitor $exhibitor)
    {
        $exhibitor->delete();

        return redirect()
            ->route('admin.exhibitors.index')
            ->with('success', 'Exhibitor deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:exhibitors,Ex_ID'],
        ]);

        DB::transaction(function () use ($data) {
            Exhibitor::query()
                ->whereIn('Ex_ID', $data['ids'])
                ->delete();
        });

        return redirect()
            ->route('admin.exhibitors.index')
            ->with('success', 'Selected exhibitors deleted');
    }
}


