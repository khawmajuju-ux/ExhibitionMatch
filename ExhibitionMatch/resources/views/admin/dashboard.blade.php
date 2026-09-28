@extends('layouts.admin')

@section('title', __('admin.pages.dashboard.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.dashboard.title') }}</span>
    </div>
@endsection

@section('content')
    <h1 class="admin-title">{{ __('admin.pages.dashboard.title') }}</h1>

    <section class="admin-stats">
        <div class="admin-stat">
            <div class="admin-stat__icon" aria-hidden="true">
                <img class="admin-stat__iconImg" src="{{ asset('images/icon_admin/user.png') }}" alt="" />
            </div>
            <div class="admin-stat__value">{{ number_format($totalUserCount) }}</div>
            <div class="admin-stat__label">{{ __('admin.pages.dashboard.stats.total_users') }}</div>
        </div>

        <div class="admin-stat">
            <div class="admin-stat__icon" aria-hidden="true">
                <img class="admin-stat__iconImg" src="{{ asset('images/icon_admin/Eye icon.png') }}" alt="" />
            </div>
            <div class="admin-stat__value">{{ number_format($visitorCount) }}</div>
            <div class="admin-stat__label">{{ __('admin.pages.dashboard.stats.visitors') }}</div>
        </div>

        <div class="admin-stat">
            <div class="admin-stat__icon" aria-hidden="true">
                <img class="admin-stat__iconImg" src="{{ asset('images/icon_admin/Floppy icon.png') }}" alt="" />
            </div>
            <div class="admin-stat__value">{{ number_format($exhibitorCount) }}</div>
            <div class="admin-stat__label">{{ __('admin.pages.dashboard.stats.exhibitors') }}</div>
        </div>
    </section>

    <section class="admin-card admin-card--table admin-card--flat">
        <div class="admin-dtWrap">
            <table class="admin-dt" aria-label="Tag ranking table">
                <thead>
                    <tr>
                        <th class="admin-dt__rank">{{ __('admin.pages.dashboard.table.rank') }}</th>
                        <th>{{ __('admin.pages.dashboard.table.tag') }}</th>
                        <th class="admin-dt__count">{{ __('admin.pages.dashboard.table.count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($base = (($tagRanking?->currentPage() ?? 1) - 1) * ($tagRanking?->perPage() ?? 10))
                    @forelse ($tagRanking as $row)
                        <tr>
                            <td class="admin-dt__rank">{{ $base + $loop->iteration }}</td>
                            <td>
                                <span
                                    class="admin-dt__tag"
                                    @if (!empty($row->tag_bg))
                                        style="--tag-bg: {{ $row->tag_bg }}; --tag-fg: {{ $row->tag_fg ?: '#ffffff' }}; --tag-border: transparent;"
                                    @endif
                                >{{ $row->tag_name }}</span>
                            </td>
                            <td class="admin-dt__count">{{ number_format($row->selections) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-dt__empty">{{ __('admin.pages.dashboard.table.empty') }}</td>
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
                <span>{{ __('admin.pages.exhibitors.footer.of_rows', ['count' => number_format($tagRanking?->total() ?? 0)]) }}</span>
                @foreach (request()->except('per_page', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endforeach
            </form>

            <div class="admin-dtFooter__right">
                {{ $tagRanking->onEachSide(1)->links('vendor.pagination.admin') }}
            </div>
        </div>
    </section>
@endsection


