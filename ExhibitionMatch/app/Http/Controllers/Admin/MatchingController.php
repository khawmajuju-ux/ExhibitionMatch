<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\ProblemTag;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MatchingController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->string('view')->toString() ?: 'exhibitor'; // exhibitor | tag
        $view = in_array($view, ['exhibitor', 'tag'], true) ? $view : 'exhibitor';

        $selectedTagId = $request->integer('tag_id') ?: null;
        $selectedExhibitorId = $request->integer('exhibitor_id') ?: null;

        $tags = ProblemTag::query()
            ->orderBy('tag_name')
            ->get(['Tag_ID', 'tag_name', 'tag_color']);

        $exhibitorOptions = Exhibitor::query()
            ->orderBy('ex_companyName')
            ->get(['Ex_ID', 'ex_companyName', 'Tag_id']);

        if ($view === 'tag') {
            $perPage = (int) $request->query('per_page', 10);
            if ($perPage < 5) {
                $perPage = 5;
            }
            if ($perPage > 100) {
                $perPage = 100;
            }

            $tagsQuery = ProblemTag::query()->orderBy('tag_name');
            if ($selectedTagId) {
                $tagsQuery->where('Tag_ID', $selectedTagId);
            }

            $tagPage = $tagsQuery
                ->paginate($perPage)
                ->withQueryString();

            $tagIds = $tagPage->getCollection()->pluck('Tag_ID');

            $exhibitorsByTagId = Exhibitor::query()
                ->whereIn('Tag_id', $tagIds)
                ->get(['Ex_ID', 'ex_companyName', 'ex_companyEmail', 'ex_companyPhone', 'Tag_id', 'created_at'])
                ->groupBy('Tag_id');

            $visitorsByTagId = Visitor::query()
                ->whereIn('Tag_id', $tagIds)
                ->get(['Visitor_ID', 'visitor_name', 'Tag_id'])
                ->groupBy('Tag_id');

            $summaries = $tagPage->getCollection()->map(function ($tag) use ($exhibitorsByTagId, $visitorsByTagId) {
                $exhibitors = $exhibitorsByTagId->get($tag->Tag_ID, collect());
                $visitors = $visitorsByTagId->get($tag->Tag_ID, collect());

                return (object) [
                    'tag_id' => $tag->Tag_ID,
                    'tag_name' => $tag->tag_name,
                    'tag_color' => $tag->tag_color,
                    'tag_bg' => $tag->cssColor(),
                    'tag_fg' => $tag->cssTextColor(),
                    'exhibitor_count' => $exhibitors->count(),
                    'visitor_count' => $visitors->count(),
                    'exhibitor_names' => $exhibitors->pluck('ex_companyName')->values(),
                    'visitor_names' => $visitors->pluck('visitor_name')->values(),
                ];
            });

            $tagPage->setCollection($summaries);

            return view('admin.matching.index', [
                'view' => $view,
                'tags' => $tags,
                'exhibitorOptions' => $exhibitorOptions,
                'selectedTagId' => $selectedTagId,
                'selectedExhibitorId' => $selectedExhibitorId,
                'tagSummaries' => $tagPage,
                'exhibitorsByTagId' => $exhibitorsByTagId,
                'matchCount' => $tagPage->total(),
                'exhibitors' => null,
                'visitorsByTagId' => collect(),
                'perPage' => $perPage,
            ]);
        }

        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 5) {
            $perPage = 5;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $query = Exhibitor::query()
            ->with('problemTag')
            ->orderByDesc('Ex_ID');

        if ($selectedExhibitorId) {
            $query->where('Ex_ID', $selectedExhibitorId);
        }
        if ($selectedTagId) {
            $query->where('Tag_id', $selectedTagId);
        }

        $exhibitors = $query->paginate($perPage)->withQueryString();

        $tagIds = $exhibitors->pluck('Tag_id')->filter()->unique()->values();
        $visitorsByTagId = Visitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->orderByDesc('Visitor_ID')
            ->get([
                'Visitor_ID',
                'visitor_name',
                'visitor_company',
                'visitor_position',
                'visitor_contact',
                'Tag_id',
                'created_at',
            ])
            ->groupBy('Tag_id');

        return view('admin.matching.index', [
            'view' => 'exhibitor',
            'tags' => $tags,
            'exhibitorOptions' => $exhibitorOptions,
            'selectedTagId' => $selectedTagId,
            'selectedExhibitorId' => $selectedExhibitorId,
            'tagSummaries' => collect(),
            'matchCount' => $exhibitors->total(),
            'exhibitors' => $exhibitors,
            'visitorsByTagId' => $visitorsByTagId,
            'perPage' => $perPage,
        ]);
    }

    public function export(Request $request)
    {
        $view = $request->string('view')->toString() ?: 'tag';
        $view = in_array($view, ['tag', 'exhibitor'], true) ? $view : 'tag';

        $selected = collect($request->input('selected', []))
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values();

        if ($selected->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'กรุณาเลือกรายการที่จะ export');
        }

        if ($view === 'exhibitor') {
            return $this->exportExhibitorsCsv($selected);
        }

        return $this->exportTagsCsv($selected);
    }

    private function buildTagSummaries(?int $selectedTagId): Collection
    {
        $tagsQuery = ProblemTag::query()->orderBy('tag_name');
        if ($selectedTagId) {
            $tagsQuery->where('Tag_ID', $selectedTagId);
        }

        $tags = $tagsQuery->get(['Tag_ID', 'tag_name', 'tag_color']);
        $tagIds = $tags->pluck('Tag_ID');

        $exhibitorsByTagId = Exhibitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->get(['Ex_ID', 'ex_companyName', 'Tag_id'])
            ->groupBy('Tag_id');

        $visitorsByTagId = Visitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->get(['Visitor_ID', 'visitor_name', 'Tag_id', 'created_at'])
            ->groupBy('Tag_id');

        return $tags->map(function ($tag) use ($exhibitorsByTagId, $visitorsByTagId) {
            $exhibitors = $exhibitorsByTagId->get($tag->Tag_ID, collect());
            $visitors = $visitorsByTagId->get($tag->Tag_ID, collect());

            return (object) [
                'tag_id' => $tag->Tag_ID,
                'tag_name' => $tag->tag_name,
                'tag_color' => $tag->tag_color,
                'exhibitor_count' => $exhibitors->count(),
                'visitor_count' => $visitors->count(),
                'exhibitor_names' => $exhibitors->pluck('ex_companyName')->values(),
                'visitor_names' => $visitors->pluck('visitor_name')->values(),
            ];
        });
    }

    private function exportTagsCsv(Collection $tagIds)
    {
        $tags = ProblemTag::query()
            ->whereIn('Tag_ID', $tagIds)
            ->orderBy('tag_name')
            ->get(['Tag_ID', 'tag_name', 'tag_color']);

        $exhibitorsByTagId = Exhibitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->get(['Ex_ID', 'ex_companyName', 'Tag_id'])
            ->groupBy('Tag_id');

        $visitorsByTagId = Visitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->get(['Visitor_ID', 'visitor_name', 'Tag_id', 'created_at'])
            ->groupBy('Tag_id');

        $filename = 'matching_by_tag_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($tags, $exhibitorsByTagId, $visitorsByTagId) {
            // UTF-8 BOM for Excel
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tag ID', 'Tag Name', 'Exhibitor Count', 'Exhibitors', 'Visitor Count', 'Visitors']);

            foreach ($tags as $tag) {
                $exhibitors = $exhibitorsByTagId->get($tag->Tag_ID, collect())->pluck('ex_companyName')->values();
                $visitors = $visitorsByTagId->get($tag->Tag_ID, collect())->pluck('visitor_name')->values();

                fputcsv($out, [
                    $tag->Tag_ID,
                    $tag->tag_name,
                    $exhibitors->count(),
                    $exhibitors->implode('; '),
                    $visitors->count(),
                    $visitors->implode('; '),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportExhibitorsCsv(Collection $exhibitorIds)
    {
        $exhibitors = Exhibitor::query()
            ->with('problemTag')
            ->whereIn('Ex_ID', $exhibitorIds)
            ->orderByDesc('Ex_ID')
            ->get();

        $tagIds = $exhibitors->pluck('Tag_id')->filter()->unique()->values();
        $visitorsByTagId = Visitor::query()
            ->whereIn('Tag_id', $tagIds)
            ->get(['Visitor_ID', 'visitor_name', 'Tag_id', 'created_at'])
            ->groupBy('Tag_id');

        $filename = 'matching_by_exhibitor_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($exhibitors, $visitorsByTagId) {
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, ['Exhibitor ID', 'Company Name', 'Email', 'Phone', 'Website', 'ProblemTag', 'Visitor Count', 'Visitors']);

            foreach ($exhibitors as $ex) {
                $visitors = $visitorsByTagId->get($ex->Tag_id, collect())->pluck('visitor_name')->values();

                fputcsv($out, [
                    $ex->Ex_ID,
                    $ex->ex_companyName,
                    $ex->ex_companyEmail,
                    $ex->ex_companyPhone,
                    $ex->ex_website,
                    $ex->problemTag?->tag_name,
                    $visitors->count(),
                    $visitors->implode('; '),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}


