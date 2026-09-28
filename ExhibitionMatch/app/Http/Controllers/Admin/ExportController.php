<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\ProblemTag;
use App\Models\Visitor;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function excel(Request $request)
    {
        $from = (string) $request->query('from', '');

        if (Str::startsWith($from, 'admin.exhibitors')) {
            return $this->exportExhibitors();
        }

        if (Str::startsWith($from, 'admin.visitors')) {
            return $this->exportVisitors();
        }

        if (Str::startsWith($from, 'admin.matching')) {
            return $this->exportMatching($request);
        }

        if (Str::startsWith($from, 'admin.problemtags')) {
            return $this->exportProblemTags();
        }

        if (Str::startsWith($from, 'admin.websettings')) {
            return $this->exportWebSettings();
        }

        return back()->with('error', 'This page does not support Excel export yet.');
    }

    private function exportExhibitors()
    {
        $rows = [];
        $rows[] = ['ID', 'Company Name', 'Email', 'Phone', 'ProblemTag', 'Registered'];

        Exhibitor::query()
            ->with('problemTag')
            ->orderByDesc('Ex_ID')
            ->chunk(500, function ($chunk) use (&$rows) {
                foreach ($chunk as $ex) {
                    $rows[] = [
                        $ex->Ex_ID,
                        (string) $ex->ex_companyName,
                        (string) ($ex->ex_companyEmail ?? ''),
                        (string) ($ex->ex_companyPhone ?? ''),
                        (string) ($ex->problemTag?->tag_name ?? ''),
                        optional($ex->created_at)->format('Y-m-d H:i:s') ?? '',
                    ];
                }
            });

        return $this->csvResponse($rows, 'exhibitors.csv');
    }

    private function exportVisitors()
    {
        $rows = [];
        $rows[] = ['ID', 'Name', 'Company', 'Position', 'Contact', 'ProblemTag', 'Registered'];

        Visitor::query()
            ->with('problemTag')
            ->orderByDesc('Visitor_ID')
            ->chunk(500, function ($chunk) use (&$rows) {
                foreach ($chunk as $v) {
                    $rows[] = [
                        $v->Visitor_ID,
                        (string) $v->visitor_name,
                        (string) ($v->visitor_company ?? ''),
                        (string) ($v->visitor_position ?? ''),
                        (string) ($v->visitor_contact ?? ''),
                        (string) ($v->problemTag?->tag_name ?? ''),
                        optional($v->created_at)->format('Y-m-d H:i:s') ?? '',
                    ];
                }
            });

        return $this->csvResponse($rows, 'visitors.csv');
    }

    private function exportProblemTags()
    {
        $rows = [];
        $rows[] = ['ID', 'Name', 'Color', 'Detail'];

        ProblemTag::query()
            ->orderByDesc('Tag_ID')
            ->chunk(500, function ($chunk) use (&$rows) {
                foreach ($chunk as $t) {
                    $rows[] = [
                        $t->Tag_ID,
                        (string) $t->tag_name,
                        (string) ($t->tag_color ?? ''),
                        (string) ($t->tag_detail ?? ''),
                    ];
                }
            });

        return $this->csvResponse($rows, 'problemtags.csv');
    }

    private function exportMatching(Request $request)
    {
        $view = (string) $request->query('view', 'exhibitor'); // exhibitor | tag
        $view = in_array($view, ['exhibitor', 'tag'], true) ? $view : 'exhibitor';

        $selectedTagId = $request->integer('tag_id') ?: null;
        $selectedExhibitorId = $request->integer('exhibitor_id') ?: null;

        if ($view === 'tag') {
            $tags = ProblemTag::query()
                ->orderBy('tag_name')
                ->when($selectedTagId, fn ($q) => $q->where('Tag_ID', $selectedTagId))
                ->get(['Tag_ID', 'tag_name', 'tag_color']);

            $tagIds = $tags->pluck('Tag_ID');

            $rows = [];
            $rows[] = [
                'Tag ID',
                'Tag Name',
                'Row Type', // TAG | EXHIBITOR | VISITOR
                'Exhibitor ID',
                'Exhibitor Company',
                'Exhibitor Email',
                'Exhibitor Phone',
                'Exhibitor Created At',
                'Visitor ID',
                'Visitor Name',
                'Visitor Company',
                'Visitor Position',
                'Visitor Contact',
                'Visitor Created At',
                'Exhibitor Count',
                'Visitor Count',
            ];

            foreach ($tags as $tag) {
                $exhibitors = Exhibitor::query()
                    ->where('Tag_id', $tag->Tag_ID)
                    ->orderByDesc('Ex_ID')
                    ->get(['Ex_ID', 'ex_companyName', 'ex_companyEmail', 'ex_companyPhone', 'Tag_id', 'created_at']);

                $visitors = Visitor::query()
                    ->where('Tag_id', $tag->Tag_ID)
                    ->orderByDesc('Visitor_ID')
                    ->get([
                        'Visitor_ID',
                        'visitor_name',
                        'visitor_company',
                        'visitor_position',
                        'visitor_contact',
                        'Tag_id',
                        'created_at',
                    ]);

                $rows[] = [
                    $tag->Tag_ID,
                    (string) $tag->tag_name,
                    'TAG',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $exhibitors->count(),
                    $visitors->count(),
                ];

                foreach ($exhibitors as $ex) {
                    $rows[] = [
                        $tag->Tag_ID,
                        (string) $tag->tag_name,
                        'EXHIBITOR',
                        $ex->Ex_ID,
                        (string) $ex->ex_companyName,
                        (string) ($ex->ex_companyEmail ?? ''),
                        (string) ($ex->ex_companyPhone ?? ''),
                        optional($ex->created_at)->format('Y-m-d H:i:s') ?? '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ];
                }

                foreach ($visitors as $v) {
                    $rows[] = [
                        $tag->Tag_ID,
                        (string) $tag->tag_name,
                        'VISITOR',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $v->Visitor_ID,
                        (string) $v->visitor_name,
                        (string) ($v->visitor_company ?? ''),
                        (string) ($v->visitor_position ?? ''),
                        (string) ($v->visitor_contact ?? ''),
                        optional($v->created_at)->format('Y-m-d H:i:s') ?? '',
                        '',
                        '',
                    ];
                }
            }

            $suffix = $selectedTagId ? ('_tag_' . $selectedTagId) : '';
            return $this->csvResponse($rows, 'matching_by_tag' . $suffix . '.csv');
        }

        $exhibitors = Exhibitor::query()
            ->with('problemTag')
            ->orderByDesc('Ex_ID')
            ->when($selectedExhibitorId, fn ($q) => $q->where('Ex_ID', $selectedExhibitorId))
            ->when($selectedTagId, fn ($q) => $q->where('Tag_id', $selectedTagId))
            ->get();

        $rows = [];
        $rows[] = [
            'Exhibitor ID',
            'Exhibitor Company',
            'Exhibitor Email',
            'Exhibitor Phone',
            'ProblemTag ID',
            'ProblemTag Name',
            'Exhibitor Created At',
            'Visitor ID',
            'Visitor Name',
            'Visitor Company',
            'Visitor Position',
            'Visitor Contact',
            'Visitor Created At',
        ];

        foreach ($exhibitors as $ex) {
            $visitors = $ex->Tag_id
                ? Visitor::query()
                    ->where('Tag_id', $ex->Tag_id)
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
                : collect();

            if ($visitors->isEmpty()) {
                $rows[] = [
                    $ex->Ex_ID,
                    (string) $ex->ex_companyName,
                    (string) ($ex->ex_companyEmail ?? ''),
                    (string) ($ex->ex_companyPhone ?? ''),
                    (string) ($ex->Tag_id ?? ''),
                    (string) ($ex->problemTag?->tag_name ?? ''),
                    optional($ex->created_at)->format('Y-m-d H:i:s') ?? '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];
                continue;
            }

            foreach ($visitors as $v) {
                $rows[] = [
                    $ex->Ex_ID,
                    (string) $ex->ex_companyName,
                    (string) ($ex->ex_companyEmail ?? ''),
                    (string) ($ex->ex_companyPhone ?? ''),
                    (string) ($ex->Tag_id ?? ''),
                    (string) ($ex->problemTag?->tag_name ?? ''),
                    optional($ex->created_at)->format('Y-m-d H:i:s') ?? '',
                    $v->Visitor_ID,
                    (string) $v->visitor_name,
                    (string) ($v->visitor_company ?? ''),
                    (string) ($v->visitor_position ?? ''),
                    (string) ($v->visitor_contact ?? ''),
                    optional($v->created_at)->format('Y-m-d H:i:s') ?? '',
                ];
            }
        }

        $suffixParts = [];
        if ($selectedTagId) {
            $suffixParts[] = 'tag_' . $selectedTagId;
        }
        if ($selectedExhibitorId) {
            $suffixParts[] = 'ex_' . $selectedExhibitorId;
        }
        $suffix = $suffixParts ? ('_' . implode('_', $suffixParts)) : '';

        return $this->csvResponse($rows, 'matching_by_exhibitor' . $suffix . '.csv');
    }

    private function exportWebSettings()
    {
        $rows = [];
        $rows[] = ['ID', 'Event Title', 'Description', 'Landing Image Path', 'Map Image Path', 'Updated At'];

        WebSetting::query()
            ->orderByDesc('Web_ID')
            ->chunk(500, function ($chunk) use (&$rows) {
                foreach ($chunk as $set) {
                    $rows[] = [
                        $set->Web_ID,
                        (string) ($set->web_title ?? ''),
                        (string) ($set->web_detail ?? ''),
                        (string) ($set->landing_image_path ?? ''),
                        (string) ($set->seminar_map_image_path ?? ''),
                        optional($set->updated_at)->format('Y-m-d H:i:s') ?? '',
                    ];
                }
            });

        return $this->csvResponse($rows, 'webdetail_history.csv');
    }

    private function csvResponse(array $rows, string $filename)
    {
        $handle = fopen('php://temp', 'r+');

        // UTF-8 BOM for Excel
        fwrite($handle, "\xEF\xBB\xBF");

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}

