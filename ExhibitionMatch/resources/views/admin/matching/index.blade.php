@extends('layouts.admin')

@section('title', __('admin.pages.matching.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.matching.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">{{ __('admin.pages.matching.title') }}</h1>
            <div class="admin-muted">{{ __('admin.pages.matching.subtitle_total', ['count' => number_format($matchCount ?? 0)]) }}</div>
        </div>
    </div>

    <section class="admin-card admin-card--form" style="margin-bottom: 14px;">
        <form method="GET" action="{{ route('admin.matching.index') }}" class="admin-filters {{ ($view ?? 'exhibitor') === 'exhibitor' ? 'admin-filters--exhibitor' : 'admin-filters--tag' }}">
            <div class="admin-form__grid">
                <div class="admin-field">
                    <label class="admin-label" for="view">{{ __('admin.pages.matching.filters.view') }}</label>
                    <select id="view" name="view" class="admin-input">
                        <option value="exhibitor" @selected(($view ?? 'exhibitor') === 'exhibitor')>{{ __('admin.pages.matching.filters.by_exhibitor') }}</option>
                        <option value="tag" @selected(($view ?? 'exhibitor') === 'tag')>{{ __('admin.pages.matching.filters.by_tag') }}</option>
                    </select>
                </div>

                @php($isExhibitorView = ($view ?? 'exhibitor') === 'exhibitor')
                <div class="admin-field" data-filter="exhibitor" @if (!$isExhibitorView) hidden @endif>
                    <label class="admin-label" for="exhibitor_id">{{ __('admin.pages.matching.filters.exhibitor_filter') }}</label>
                    <select id="exhibitor_id" name="exhibitor_id" class="admin-input" @disabled(!$isExhibitorView)>
                        <option value="">-</option>
                        @foreach ($exhibitorOptions as $ex)
                            <option value="{{ $ex->Ex_ID }}" @selected((int) ($selectedExhibitorId ?? 0) === (int) $ex->Ex_ID)>
                                {{ $ex->ex_companyName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field" data-filter="tag" @if ($isExhibitorView) hidden @endif>
                    <label class="admin-label" for="tag_id">{{ __('admin.pages.matching.filters.tag_filter') }}</label>
                    <select id="tag_id" name="tag_id" class="admin-input" @disabled($isExhibitorView)>
                        <option value="">-</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->Tag_ID }}" @selected((int) ($selectedTagId ?? 0) === (int) $tag->Tag_ID)>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field" style="display:flex;align-items:flex-end;gap:10px;">
                    <button type="submit" class="admin-btn admin-btn--primary">{{ __('admin.pages.matching.filters.apply') }}</button>
                    <a class="admin-btn admin-btn--ghost" href="{{ route('admin.matching.index', ['view' => ($view ?? 'exhibitor')]) }}">{{ __('admin.pages.matching.filters.reset') }}</a>
                </div>
            </div>
        </form>
        <div class="admin-muted" style="margin-top: 10px;">
            {{ __('admin.pages.matching.export.excel_hint') }}
        </div>
    </section>

    @if (($view ?? 'exhibitor') === 'tag')
        <section class="admin-card admin-card--table admin-card--flat">
            <div class="admin-dtWrap">
                <table class="admin-dt admin-dt--matching" aria-label="Matching by tag table">
                    <thead>
                        <tr>
                            <th class="admin-dt__id">{{ __('admin.pages.exhibitors.table.id') }}</th>
                            <th>{{ __('admin.pages.exhibitors.table.company') }}</th>
                            <th>{{ __('admin.pages.exhibitors.table.contact') }}</th>
                            <th class="admin-dt__date">{{ __('admin.pages.exhibitors.table.created_at') }}</th>
                            <th class="admin-match__eyeTh"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagSummaries as $row)
                            @php($groupId = 'tag-' . $row->tag_id)
                            @php($exList = $exhibitorsByTagId?->get($row->tag_id, collect()) ?? collect())
                            <tr class="admin-match__group" data-group="{{ $groupId }}">
                                <td class="admin-dt__id">{{ $row->tag_id }}</td>
                                <td>
                                    <span
                                        class="admin-dt__tag"
                                        @if (!empty($row->tag_bg))
                                            style="--tag-bg: {{ $row->tag_bg }}; --tag-fg: {{ $row->tag_fg ?: '#ffffff' }}; --tag-border: transparent;"
                                        @endif
                                    >{{ $row->tag_name }}</span>
                                </td>
                                <td class="admin-dt__contact">
                                    <div class="admin-dt__muted">
                                        Ex: {{ number_format($row->exhibitor_count) }} | Vis: {{ number_format($row->visitor_count) }}
                                    </div>
                                </td>
                                <td class="admin-dt__date">-</td>
                                <td class="admin-match__eyeTd">
                                    <button
                                        type="button"
                                        class="admin-eyeBtn"
                                        data-match-toggle="{{ $groupId }}"
                                        aria-label="{{ __('admin.pages.matching.table.toggle_details') }}"
                                        title="{{ __('admin.pages.matching.table.toggle_details') }}"
                                        aria-expanded="false"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            @if ($exList->isNotEmpty())
                                <tr class="admin-match__sep" data-parent="{{ $groupId }}" hidden>
                                    <td colspan="5">
                                        <div class="admin-matchSep">
                                            <span>{{ __('admin.pages.matching.tables.exhibitors') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @foreach ($exList as $ex)
                                <tr class="admin-match__child" data-parent="{{ $groupId }}" hidden>
                                    <td class="admin-dt__id">{{ $ex->Ex_ID }}</td>
                                    <td class="admin-dt__company">
                                        <div class="admin-dt__primary">{{ $ex->ex_companyName }}</div>
                                    </td>
                                    <td class="admin-dt__contact">
                                        <div>{{ $ex->ex_companyEmail ?: '-' }}</div>
                                        @if (!empty($ex->ex_companyPhone))
                                            <div class="admin-dt__muted">{{ $ex->ex_companyPhone }}</div>
                                        @endif
                                    </td>
                                    <td class="admin-dt__date">{{ optional($ex->created_at)->format('Y-m-d H:i') }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="admin-dt__empty">{{ __('admin.pages.matching.tables.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-dtFooter">
                <form method="GET" class="admin-dtFooter__left" id="per-page-form">
                    <span>{{ __('admin.pages.exhibitors.footer.rows_per_page') }}</span>
                    <select name="per_page" class="admin-dtFooter__select" onchange="document.getElementById('per-page-form').submit()">
                        @foreach ([10, 15, 25, 50] as $size)
                            <option value="{{ $size }}" @selected((int) ($perPage ?? 10) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>{{ __('admin.pages.exhibitors.footer.of_rows', ['count' => number_format($matchCount ?? 0)]) }}</span>
                    @foreach (request()->except('per_page', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                </form>

                <div class="admin-dtFooter__right">
                    {{ $tagSummaries->onEachSide(1)->links('vendor.pagination.admin') }}
                </div>
            </div>
        </section>
    @else
    <section class="admin-card admin-card--table admin-card--flat">
        <div class="admin-dtWrap">
            <table class="admin-dt admin-dt--matching" aria-label="Matching table">
                <thead>
                    <tr>
                        <th class="admin-match__rankTh">{{ __('admin.pages.matching.table.rank') }}</th>
                        <th>{{ __('admin.pages.matching.table.company') }}</th>
                        <th class="admin-match__midTh">{{ __('admin.pages.matching.table.phone_or_position') }}</th>
                        <th class="admin-match__midTh">{{ __('admin.pages.matching.table.email_or_contact') }}</th>
                        <th class="admin-match__tagTh">{{ __('admin.pages.matching.table.category') }}</th>
                        <th class="admin-match__dateTh">{{ __('admin.pages.matching.table.created_at') }}</th>
                        <th class="admin-match__eyeTh"></th>
                    </tr>
                </thead>
                <tbody>
                    @php($base = (($exhibitors?->currentPage() ?? 1) - 1) * ($exhibitors?->perPage() ?? 10))
                    @forelse ($exhibitors as $ex)
                        @php($rank = str_pad((int) ($base + $loop->iteration), 3, '0', STR_PAD_LEFT))
                        @php($visitors = $ex->Tag_id ? ($visitorsByTagId->get($ex->Tag_id, collect())) : collect())
                        <tr class="admin-match__group" data-group="match-{{ $ex->Ex_ID }}">
                            <td class="admin-match__rankTd">{{ $rank }}</td>
                            <td class="admin-dt__company">
                                <div class="admin-dt__primary">{{ $ex->ex_companyName }}</div>
                            </td>
                            <td>{{ $ex->ex_companyPhone ?: '-' }}</td>
                            <td>{{ $ex->ex_companyEmail ?: '-' }}</td>
                            <td>
                                @php($tag = $ex->problemTag)
                                @php($tagName = $tag?->tag_name ?: '-')
                                @php($tagBg = $tag?->cssColor())
                                @php($tagFg = $tag?->cssTextColor())
                                <span
                                    class="admin-dt__tag"
                                    @if ($tagBg)
                                        style="--tag-bg: {{ $tagBg }}; --tag-fg: {{ $tagFg ?: '#ffffff' }}; --tag-border: transparent;"
                                    @endif
                                >{{ $tagName }}</span>
                            </td>
                            <td class="admin-match__dateTd">{{ optional($ex->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="admin-match__eyeTd">
                                <button
                                    type="button"
                                    class="admin-eyeBtn"
                                    data-match-toggle="match-{{ $ex->Ex_ID }}"
                                    aria-label="{{ __('admin.pages.matching.table.toggle_details') }}"
                                    title="{{ __('admin.pages.matching.table.toggle_details') }}"
                                    aria-expanded="false"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        @if ($visitors->isNotEmpty())
                            <tr class="admin-match__sep" data-parent="match-{{ $ex->Ex_ID }}" hidden>
                                <td colspan="7">
                                    <div class="admin-matchSep">
                                        <span>{{ __('admin.pages.matching.table.matched_visitors') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @foreach ($visitors as $v)
                            <tr class="admin-match__child" data-parent="match-{{ $ex->Ex_ID }}" hidden>
                                <td class="admin-match__rankTd">{{ $loop->iteration }}</td>
                                <td>{{ $v->visitor_company ?: '-' }}</td>
                                <td>{{ $v->visitor_position ?: '-' }}</td>
                                <td>{{ $v->visitor_contact ?: '-' }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="admin-dt__empty">{{ __('admin.pages.matching.tables.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-dtFooter">
            <form method="GET" class="admin-dtFooter__left" id="per-page-form">
                <span>{{ __('admin.pages.exhibitors.footer.rows_per_page') }}</span>
                <select name="per_page" class="admin-dtFooter__select" onchange="document.getElementById('per-page-form').submit()">
                    @foreach ([10, 15, 25, 50] as $size)
                        <option value="{{ $size }}" @selected((int) ($perPage ?? 10) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
                <span>{{ __('admin.pages.exhibitors.footer.of_rows', ['count' => number_format($matchCount ?? 0)]) }}</span>
                @foreach (request()->except('per_page', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endforeach
            </form>

            <div class="admin-dtFooter__right">
                {{ $exhibitors->onEachSide(1)->links('vendor.pagination.admin') }}
            </div>
        </div>
    </section>

    @endif

    <script>
        (function () {
            const viewSelect = document.getElementById('view');
            const exhibitorField = document.querySelector('[data-filter="exhibitor"]');
            const tagField = document.querySelector('[data-filter="tag"]');
            const exhibitorSelect = document.getElementById('exhibitor_id');
            const tagSelect = document.getElementById('tag_id');
            const filterForm = viewSelect?.closest('form');

            function syncFilterUi() {
                const view = viewSelect?.value || 'exhibitor';
                const isEx = view === 'exhibitor';

                if (exhibitorField) exhibitorField.hidden = !isEx;
                if (tagField) tagField.hidden = isEx;
                if (exhibitorSelect) exhibitorSelect.disabled = !isEx;
                if (tagSelect) tagSelect.disabled = isEx;

                // Avoid "stale" param being submitted when switching modes.
                if (isEx && tagSelect && tagSelect.value) tagSelect.value = '';
                if (!isEx && exhibitorSelect && exhibitorSelect.value) exhibitorSelect.value = '';

                // Keep grid class consistent (mostly cosmetic)
                if (filterForm) {
                    filterForm.classList.toggle('admin-filters--exhibitor', isEx);
                    filterForm.classList.toggle('admin-filters--tag', !isEx);
                }
            }

            viewSelect?.addEventListener('change', syncFilterUi);
            syncFilterUi();

            const buttons = Array.from(document.querySelectorAll('[data-match-toggle]'));
            if (buttons.length === 0) return;

            function toggle(groupId, forceExpanded) {
                const rows = Array.from(document.querySelectorAll('[data-parent="' + groupId + '"]'));
                if (rows.length === 0) return;

                const currentlyHidden = rows[0].hidden === true;
                const willExpand = typeof forceExpanded === 'boolean' ? forceExpanded : currentlyHidden;
                rows.forEach((r) => (r.hidden = !willExpand));

                const btn = document.querySelector('[data-match-toggle="' + groupId + '"]');
                btn?.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
                btn?.classList.toggle('is-collapsed', !willExpand);
            }

            buttons.forEach((btn) => {
                const groupId = btn.getAttribute('data-match-toggle');
                btn.addEventListener('click', () => toggle(groupId));
                // default collapsed (show only group row until user clicks)
                btn.classList.add('is-collapsed');
                toggle(groupId, false);
            });
        })();
    </script>
@endsection


