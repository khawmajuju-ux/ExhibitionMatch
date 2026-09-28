@extends('layouts.admin')

@section('title', __('admin.pages.problemtags.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.nav.setting') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.problemtags.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">{{ __('admin.pages.problemtags.title') }}</h1>
        </div>
    </div>

    <div class="admin-tagPage">
        <section class="admin-card admin-card--form admin-tagPage__list">
            <div class="admin-tagPage__title">{{ __('admin.pages.problemtags.list_title') }}</div>

            @forelse ($tags as $t)
                <div class="admin-tagCard">
                    <div class="admin-tagCard__top">
                        @php($bg = $t->cssColor() ?: '#9CA3AF')
                        @php($fg = $t->cssTextColor() ?: '#111827')
                        <div class="admin-tagCard__badge" style="background: {{ $bg }}; color: {{ $fg }};">
                            {{ $t->tag_name }}
                        </div>
                        <div class="admin-tagCard__actions">
                            <a
                                class="admin-iconBtn"
                                href="{{ route('admin.problemtags.edit', $t) }}"
                                aria-label="{{ __('admin.pages.problemtags.table.edit') }}"
                                title="{{ __('admin.pages.problemtags.table.edit') }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.problemtags.destroy', $t) }}" onsubmit="return confirm('{{ __('admin.pages.problemtags.confirm_delete') }}');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="admin-iconBtn admin-iconBtn--danger"
                                    aria-label="{{ __('admin.pages.problemtags.table.delete') }}"
                                    title="{{ __('admin.pages.problemtags.table.delete') }}"
                                >
                                    <img src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (!empty($t->tag_detail))
                        <div class="admin-tagCard__desc">{{ $t->tag_detail }}</div>
                    @endif

                    <div class="admin-tagCard__date">{{ optional($t->created_at)->format('Y-m-d') }}</div>
                </div>
            @empty
                <div class="admin-muted">{{ __('admin.pages.problemtags.table.empty') }}</div>
            @endforelse

            <div class="admin-dtFooter" style="padding: 14px 0 0;">
                <form method="GET" class="admin-dtFooter__left" id="per-page-form">
                    <span>{{ __('admin.pages.problemtags.footer.rows_per_page') }}</span>
                    <select name="per_page" class="admin-dtFooter__select" onchange="document.getElementById('per-page-form').submit()">
                        @foreach ([10, 15, 25, 50] as $size)
                            <option value="{{ $size }}" @selected((int) ($perPage ?? 15) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>{{ __('admin.pages.problemtags.footer.of_rows', ['count' => number_format($tagCount ?? 0)]) }}</span>
                    @foreach (request()->except('per_page', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                </form>

                <div class="admin-dtFooter__right">
                    {{ $tags->onEachSide(1)->links('vendor.pagination.admin') }}
                </div>
            </div>
        </section>

        <section class="admin-card admin-card--form admin-tagPage__form">
            <div class="admin-tagPage__title">{{ __('admin.pages.problemtags.add_title') }}</div>
            <div class="admin-muted" style="margin-top:6px;">{{ __('admin.pages.problemtags.add_subtitle') }}</div>

            <form method="POST" action="{{ route('admin.problemtags.store') }}" class="admin-form" style="margin-top: 12px;">
                @csrf

                <div class="admin-field">
                    <label class="admin-label" for="tag_name">{{ __('admin.pages.problemtags_form.fields.name') }}</label>
                    <input
                        id="tag_name"
                        name="tag_name"
                        class="admin-input @error('tag_name') is-invalid @enderror"
                        value="{{ old('tag_name', $tag->tag_name) }}"
                        maxlength="100"
                        required
                        placeholder="{{ __('admin.pages.problemtags.name_placeholder') }}"
                    />
                    @error('tag_name')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field" style="margin-top: 12px;">
                    <label class="admin-label" for="tag_detail">{{ __('admin.pages.problemtags_form.fields.detail') }}</label>
                    <textarea
                        id="tag_detail"
                        name="tag_detail"
                        class="admin-textarea @error('tag_detail') is-invalid @enderror"
                        rows="4"
                        placeholder="{{ __('admin.pages.problemtags.detail_placeholder') }}"
                    >{{ old('tag_detail', $tag->tag_detail) }}</textarea>
                    @error('tag_detail')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field" style="margin-top: 12px;">
                    <label class="admin-label" for="tag_color">{{ __('admin.pages.problemtags_form.fields.color') }}</label>
                    <input
                        id="tag_color"
                        name="tag_color"
                        class="admin-input @error('tag_color') is-invalid @enderror"
                        value="{{ old('tag_color', $tag->tag_color) }}"
                        maxlength="20"
                        placeholder="#FF0000"
                    />
                    @error('tag_color')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex; justify-content:center; margin-top: 14px;">
                    <button type="submit" class="admin-btn admin-btn--dark">{{ __('admin.pages.problemtags.add_button') }}</button>
                </div>
            </form>
        </section>
    </div>
@endsection


