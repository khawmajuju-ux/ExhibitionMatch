<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
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

        $visitors = Visitor::query()
            ->with('problemTag')
            ->orderByDesc('Visitor_ID')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.visitors.index', [
            'visitorCount' => $visitors->total(),
            'visitors' => $visitors,
            'perPage' => $perPage,
        ]);
    }

    public function show(Visitor $visitor)
    {
        $visitor->load('problemTag');

        return view('admin.users.visitors.show', [
            'visitor' => $visitor,
        ]);
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return redirect()
            ->route('admin.visitors.index')
            ->with('success', 'Visitor deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:visitors,Visitor_ID'],
        ]);

        DB::transaction(function () use ($data) {
            Visitor::query()
                ->whereIn('Visitor_ID', $data['ids'])
                ->delete();
        });

        return redirect()
            ->route('admin.visitors.index')
            ->with('success', 'Selected visitors deleted');
    }
}


