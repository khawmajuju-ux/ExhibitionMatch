<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exhibitor;
use App\Models\ProblemTag;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $visitorCount = Visitor::query()->count();
        $exhibitorCount = Exhibitor::query()->count();
        $totalUserCount = $visitorCount + $exhibitorCount;

        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 5) {
            $perPage = 5;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        // Show ALL problem tags, including those with 0 selections
        $tagRanking = DB::table('problemtags')
            ->leftJoin('visitors', 'visitors.Tag_id', '=', 'problemtags.Tag_ID')
            ->select([
                'problemtags.Tag_ID as tag_id',
                'problemtags.tag_name as tag_name',
                'problemtags.tag_color as tag_color',
                DB::raw('count(visitors.Visitor_ID) as selections'),
            ])
            ->groupBy('problemtags.Tag_ID', 'problemtags.tag_name', 'problemtags.tag_color')
            ->orderByDesc('selections')
            ->orderByDesc('problemtags.Tag_ID')
            ->paginate($perPage)
            ->withQueryString();

        $tagRanking->setCollection(
            $tagRanking->getCollection()->map(function ($row) {
                $tag = new ProblemTag(['tag_color' => $row->tag_color]);
                $row->tag_bg = $tag->cssColor();
                $row->tag_fg = $tag->cssTextColor();
                return $row;
            })
        );

        return view('admin.dashboard', [
            'totalUserCount' => $totalUserCount,
            'visitorCount' => $visitorCount,
            'exhibitorCount' => $exhibitorCount,
            'tagRanking' => $tagRanking,
            'perPage' => $perPage,
        ]);
    }
}


