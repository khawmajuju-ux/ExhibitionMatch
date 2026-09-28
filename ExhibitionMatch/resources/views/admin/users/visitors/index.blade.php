@extends('layouts.admin')

@section('title', __('admin.pages.visitors.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.breadcrumbs.user') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.visitors.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pageTitle">
        <h1 class="admin-pageTitle__h">{{ __('admin.pages.visitors.title') }}</h1>
        <div class="admin-pageTitle__sub">{{ __('admin.pages.visitors.subtitle_total', ['count' => number_format($visitorCount)]) }}</div>
    </div>

    <section class="admin-card admin-card--table admin-card--flat">
        <div id="visitors-table-tools" class="admin-tableTools is-hidden">
            <form id="visitors-bulk-delete-form" method="POST" action="{{ route('admin.visitors.bulkDestroy') }}">
                @csrf
                @method('DELETE')
                <button
                    id="visitors-bulk-delete-btn"
                    type="submit"
                    class="admin-btn admin-btn--danger"
                    aria-label="{{ __('admin.pages.visitors.table.delete_selected') }}"
                    title="{{ __('admin.pages.visitors.table.delete_selected') }}"
                >
                    <img class="admin-btn__icon" src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                    <span>{{ __('admin.pages.visitors.table.delete_selected') }}</span>
                </button>
            </form>
        </div>
        <div class="admin-dtWrap">
            <table class="admin-dt" aria-label="Visitors table">
                <thead>
                    <tr>
                        <th class="admin-dt__check">
                            <input id="visitors-check-all" type="checkbox" aria-label="Select all visitors" />
                        </th>
                        <th class="admin-dt__id">{{ __('admin.pages.visitors.table.id') }}</th>
                        <th>{{ __('admin.pages.visitors.table.name') }}</th>
                        <th>{{ __('admin.pages.visitors.table.company') }}</th>
                        <th>{{ __('admin.pages.visitors.table.position') }}</th>
                        <th>{{ __('admin.pages.visitors.table.contact') }}</th>
                        <th>{{ __('admin.pages.visitors.table.category') }}</th>
                        <th class="admin-dt__date">{{ __('admin.pages.visitors.table.created_at') }}</th>
                        <th class="admin-dt__actions">{{ __('admin.pages.visitors.table.manage') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitors as $visitor)
                        <tr>
                            <td class="admin-dt__check">
                                <input class="visitor-check" type="checkbox" value="{{ $visitor->Visitor_ID }}" aria-label="Select visitor {{ $visitor->Visitor_ID }}" />
                            </td>
                            <td class="admin-dt__id">{{ $visitor->Visitor_ID }}</td>
                            <td class="admin-dt__company">
                                <div class="admin-dt__primary">{{ $visitor->visitor_name }}</div>
                            </td>
                            <td>{{ $visitor->visitor_company ?: '-' }}</td>
                            <td>{{ $visitor->visitor_position ?: '-' }}</td>
                            <td class="admin-dt__contact">
                                <div>{{ $visitor->visitor_contact ?: '-' }}</div>
                            </td>
                            <td>
                                @php($tag = $visitor->problemTag)
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
                            <td class="admin-dt__date">{{ optional($visitor->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="admin-dt__actions">
                                <a
                                    class="admin-iconBtn"
                                    href="{{ route('admin.visitors.show', $visitor) }}"
                                    aria-label="{{ __('admin.pages.visitors.table.view') }}"
                                    title="{{ __('admin.pages.visitors.table.view') }}"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.visitors.destroy', $visitor) }}" onsubmit="return confirm('{{ __('admin.pages.visitors.confirm_delete') }}');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="admin-iconBtn admin-iconBtn--danger"
                                        aria-label="{{ __('admin.pages.visitors.table.delete') }}"
                                        title="{{ __('admin.pages.visitors.table.delete') }}"
                                    >
                                        <img src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="admin-dt__empty">{{ __('admin.pages.visitors.table.empty') }}</td>
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
                <span>{{ __('admin.pages.exhibitors.footer.of_rows', ['count' => number_format($visitorCount)]) }}</span>
                @foreach (request()->except('per_page', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endforeach
            </form>

            <div class="admin-dtFooter__right">
                {{ $visitors->onEachSide(1)->links('vendor.pagination.admin') }}
            </div>
        </div>
    </section>

    <script>
        (function () {
            const checkAll = document.getElementById('visitors-check-all');
            const checkboxes = Array.from(document.querySelectorAll('.visitor-check'));
            const tools = document.getElementById('visitors-table-tools');
            const bulkForm = document.getElementById('visitors-bulk-delete-form');

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
                if (!confirm('{{ __('admin.pages.visitors.confirm_delete_selected') }}')) {
                    e.preventDefault();
                }
            });

            syncUi();
        })();
    </script>
@endsection


