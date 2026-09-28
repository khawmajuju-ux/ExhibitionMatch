<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use App\Models\ProblemTag;
use App\Models\Visitor;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class VisitorRegistrationController extends Controller
{
    private const VISITOR_ID_COOKIE = 'em_visitor_id';
    private const VISITOR_ID_SESSION_KEY = 'visitor_id';
    private const VISITOR_ID_COOKIE_MINUTES = 60 * 24 * 30; // 30 days

    public function create()
    {
        $tags = ProblemTag::query()
            ->orderBy('tag_name')
            ->get();

        $settings = WebSetting::query()->orderByDesc('Web_ID')->first();

        return view('visitor.register', [
            'tags' => $tags,
            'introText' => $settings?->web_detail,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:150'],
            'visitor_company' => ['nullable', 'string', 'max:150'],
            'visitor_position' => ['nullable', 'string', 'max:100'],
            'visitor_contact' => ['nullable', 'string', 'max:100'],
            'Tag_id' => ['nullable', 'integer', 'exists:problemtags,Tag_ID'],
        ]);

        $visitor = Visitor::query()->create($validated);
        $request->session()->put(self::VISITOR_ID_SESSION_KEY, $visitor->Visitor_ID);

        return redirect()
            ->route('visitor.matching', $visitor)
            ->cookie(self::VISITOR_ID_COOKIE, (string) $visitor->Visitor_ID, self::VISITOR_ID_COOKIE_MINUTES)
            ->with('success', 'ลงทะเบียนสำเร็จ ขอบคุณครับ/ค่ะ');
    }

    public function matchingLatest(Request $request)
    {
        $isVisiter = str_contains($request->path(), 'visiter');
        $matchingRoute = $isVisiter ? 'visiter.matching' : 'visitor.matching';
        $registerRoute = $isVisiter ? 'visiter.register' : 'visitor.register';

        $visitorId = $this->resolveCurrentVisitorId($request);
        if ($visitorId) {
            $visitor = Visitor::query()->find($visitorId);
            if ($visitor) {
                return redirect()->route($matchingRoute, $visitor);
            }

            // Stale cookie/session (visitor removed) -> clear and re-register.
            $request->session()->forget(self::VISITOR_ID_SESSION_KEY);
            Cookie::queue(Cookie::forget(self::VISITOR_ID_COOKIE));
        }

        return redirect()
            ->route($registerRoute)
            ->with('error', 'ยังไม่มีข้อมูลของคุณในอุปกรณ์นี้ กรุณาลงทะเบียนก่อน');
    }

    public function matching(Visitor $visitor)
    {
        // Prevent direct access to other visitors by guessing the URL.
        $currentVisitorId = $this->resolveCurrentVisitorId(request());
        if (! $currentVisitorId || (int) $currentVisitorId !== (int) $visitor->Visitor_ID) {
            return redirect()
                ->route(str_contains(request()->path(), 'visiter') ? 'visiter.matching.latest' : 'visitor.matching.latest')
                ->with('error', 'ไม่สามารถเข้าถึงข้อมูลนี้ได้ กรุณาเปิดจากข้อมูลของคุณ');
        }

        $visitor->load('problemTag');

        $exhibitors = Exhibitor::query()
            ->with('problemTag')
            ->when($visitor->Tag_id, fn ($q) => $q->where('Tag_id', $visitor->Tag_id))
            ->orderBy('ex_companyName')
            ->get();

        // Ensure $exhibitors is always a Collection (never null)
        if (!$exhibitors instanceof \Illuminate\Support\Collection) {
            $exhibitors = collect([]);
        }

        $settings = WebSetting::query()->orderByDesc('Web_ID')->first();
        $mapImageUrl = null;
        if ($settings?->seminar_map_image_path) {
            // Use asset() so host/port always matches the current request (avoids APP_URL mismatch).
            $mapImageUrl = asset('storage/' . ltrim($settings->seminar_map_image_path, '/'));
        }

        return view('visitor.matching', [
            'visitor' => $visitor,
            'exhibitors' => $exhibitors,
            'mapImageUrl' => $mapImageUrl,
        ]);
    }

    private function resolveCurrentVisitorId(Request $request): ?string
    {
        $visitorId = $request->session()->get(self::VISITOR_ID_SESSION_KEY);
        if ($visitorId) {
            return (string) $visitorId;
        }

        $cookieVisitorId = $request->cookie(self::VISITOR_ID_COOKIE);
        if ($cookieVisitorId) {
            // Restore into session for this browser session.
            $request->session()->put(self::VISITOR_ID_SESSION_KEY, $cookieVisitorId);
            return (string) $cookieVisitorId;
        }

        return null;
    }
}


