@extends('layouts.admin')

@section('title', __('admin.pages.exhibitors.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.breadcrumbs.user') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.exhibitors.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pageTitle">
        <h1 class="admin-pageTitle__h">{{ __('admin.pages.exhibitors.title') }}</h1>
        <div class="admin-pageTitle__sub">{{ __('admin.pages.exhibitors.subtitle_total', ['count' => number_format($exhibitorCount)]) }}</div>
    </div>

    <div class="admin-pagehead" style="margin-top: 0;">
        <div></div>
        <a class="admin-btn admin-btn--primary" href="{{ route('admin.exhibitors.create') }}">{{ __('admin.pages.exhibitors.add') }}</a>
    </div>

    <section class="admin-card admin-card--table admin-card--flat">
        <div id="exhibitors-table-tools" class="admin-tableTools is-hidden">
            <form id="exhibitors-bulk-delete-form" method="POST" action="{{ route('admin.exhibitors.bulkDestroy') }}">
                @csrf
                @method('DELETE')
                <button
                    id="exhibitors-bulk-delete-btn"
                    type="submit"
                    class="admin-btn admin-btn--danger"
                    aria-label="{{ __('admin.pages.exhibitors.table.delete_selected') }}"
                    title="{{ __('admin.pages.exhibitors.table.delete_selected') }}"
                >
                    <img class="admin-btn__icon" src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                    <span>{{ __('admin.pages.exhibitors.table.delete_selected') }}</span>
                </button>
            </form>
        </div>
        <div class="admin-dtWrap">
            <table class="admin-dt" aria-label="Exhibitors table">
                <thead>
                    <tr>
                        <th class="admin-dt__check">
                            <input id="exhibitors-check-all" type="checkbox" aria-label="Select all exhibitors" />
                        </th>
                        <th class="admin-dt__id">{{ __('admin.pages.exhibitors.table.id') }}</th>
                        <th>{{ __('admin.pages.exhibitors.table.company') }}</th>
                        <th>{{ __('admin.pages.exhibitors.table.contact') }}</th>
                        <th>{{ __('admin.pages.exhibitors.table.category') }}</th>
                        <th class="admin-dt__date">{{ __('admin.pages.exhibitors.table.created_at') }}</th>
                        <th class="admin-dt__actions">{{ __('admin.pages.exhibitors.table.manage') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exhibitors as $exhibitor)
                        <tr>
                            <td class="admin-dt__check">
                                <input class="exhibitor-check" type="checkbox" value="{{ $exhibitor->Ex_ID }}" aria-label="Select exhibitor {{ $exhibitor->Ex_ID }}" />
                            </td>
                            <td class="admin-dt__id">{{ $exhibitor->Ex_ID }}</td>
                            <td class="admin-dt__company">
                                <div class="admin-dt__primary">{{ $exhibitor->ex_companyName }}</div>
                            </td>
                            <td class="admin-dt__contact">
                                <div>{{ $exhibitor->ex_companyEmail ?: '-' }}</div>
                                @if (!empty($exhibitor->ex_companyPhone))
                                    <div class="admin-dt__muted">{{ $exhibitor->ex_companyPhone }}</div>
                                @endif
                            </td>
                            <td>
                                @php($tag = $exhibitor->problemTag)
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
                            <td class="admin-dt__date">{{ optional($exhibitor->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="admin-dt__actions">
                                <a
                                    class="admin-iconBtn"
                                    href="{{ route('admin.exhibitors.show', $exhibitor) }}"
                                    aria-label="{{ __('admin.pages.exhibitors.table.view') }}"
                                    title="{{ __('admin.pages.exhibitors.table.view') }}"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </a>
                                <a
                                    class="admin-iconBtn"
                                    href="{{ route('admin.exhibitors.edit', $exhibitor) }}"
                                    aria-label="{{ __('admin.pages.exhibitors.table.edit') }}"
                                    title="{{ __('admin.pages.exhibitors.table.edit') }}"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.exhibitors.destroy', $exhibitor) }}" onsubmit="return confirm('{{ __('admin.pages.exhibitors.confirm_delete') }}');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="admin-iconBtn admin-iconBtn--danger"
                                        aria-label="{{ __('admin.pages.exhibitors.table.delete') }}"
                                        title="{{ __('admin.pages.exhibitors.table.delete') }}"
                                    >
                                        <img src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-dt__empty">{{ __('admin.pages.exhibitors.table.empty') }}</td>
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
                <span>{{ __('admin.pages.exhibitors.footer.of_rows', ['count' => number_format($exhibitorCount)]) }}</span>
                @foreach (request()->except('per_page', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endforeach
            </form>

            <div class="admin-dtFooter__right">
                {{ $exhibitors->onEachSide(1)->links('vendor.pagination.admin') }}
            </div>
        </div>
    </section>

    <script>
        (function () {
            const checkAll = document.getElementById('exhibitors-check-all');
            const checkboxes = Array.from(document.querySelectorAll('.exhibitor-check'));
            const tools = document.getElementById('exhibitors-table-tools');
            const bulkForm = document.getElementById('exhibitors-bulk-delete-form');

            function syncUi() {
                const selected = checkboxes.filter((c) => c.checked).length;
                if (checkboxes.length === 0) {
                    checkAll.checked = false;
                    checkAll.indeterminate = false;
                    tools?.classList.add('is-hidden');
                    return;
                }
                checkAll.checked = selected === checkboxes.length;
                checkAll.indeterminate = selected > 0 && selected < checkboxes.length;
                tools?.classList.toggle('is-hidden', selected === 0);

                // sync hidden ids[] for bulk delete
                if (bulkForm) {
                    bulkForm.querySelectorAll('input[name="ids[]"]').forEach((el) => el.remove());
                    checkboxes
                        .filter((c) => c.checked)
                        .forEach((c) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = c.value;
                            bulkForm.appendChild(input);
                        });
                }
            }

            checkAll?.addEventListener('change', () => {
                const checked = checkAll.checked;
                checkboxes.forEach((c) => (c.checked = checked));
                syncUi();
            });
            checkboxes.forEach((c) => c.addEventListener('change', syncUi));

            bulkForm?.addEventListener('submit', (e) => {
                const selected = checkboxes.filter((c) => c.checked).length;
                if (selected === 0) {
                    e.preventDefault();
                    return;
                }
                if (!confirm('{{ __('admin.pages.exhibitors.confirm_delete_selected') }}')) {
                    e.preventDefault();
                }
            });

            syncUi();
        })();
    </script>
@endsection


