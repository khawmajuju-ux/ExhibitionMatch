@extends('layouts.admin')

@section('title', __('admin.pages.websettings.title'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.nav.setting') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.websettings.title') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin:0;">{{ __('admin.pages.websettings.title') }}</h1>
            <div class="admin-muted">{{ __('admin.pages.websettings.subtitle') }}</div>
        </div>
    </div>

    @php($activeId = !empty($viewSet?->Web_ID) ? $viewSet->getKey() : null)
    @php($clearPanelUrl = route('admin.websettings.index', request()->except('page', 'view')))

    <div class="admin-tagPage admin-webdetailPage">
        <section class="admin-card admin-card--form admin-webdetailPage__list">
            <div class="admin-tagPage__title">{{ __('admin.pages.websettings.history') }}</div>

            @forelse ($sets as $item)
                @php($isActive = $activeId && (int) $activeId === (int) $item->getKey())
                @php($isViewingThis = !empty($viewId) && (int) $viewId === (int) $item->getKey())
                <div class="admin-wsCard {{ $isActive ? 'is-active' : '' }}">
                    <div class="admin-wsCard__top">
                        <div class="admin-wsCard__title">
                            {{ $item->web_title ?: __('admin.pages.websettings.table.no_title') }}
                        </div>

                        <div class="admin-wsCard__actions">
                            <a
                                class="admin-iconBtn"
                                href="{{ $isViewingThis ? $clearPanelUrl : route('admin.websettings.index', array_merge(request()->except('page', 'view'), ['view' => $item->getKey()])) }}"
                                aria-label="{{ __('admin.pages.websettings.table.view') }}"
                                title="{{ __('admin.pages.websettings.table.view') }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </a>

                            <a
                                class="admin-iconBtn"
                                href="{{ route('admin.websettings.edit', $item) }}"
                                aria-label="{{ __('admin.pages.websettings.table.edit') }}"
                                title="{{ __('admin.pages.websettings.table.edit') }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.websettings.destroy', $item) }}"
                                onsubmit="return confirm('{{ __('admin.pages.websettings.confirm_delete') }}');"
                                style="display:inline;"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="admin-iconBtn admin-iconBtn--danger"
                                    aria-label="{{ __('admin.pages.websettings.table.delete') }}"
                                    title="{{ __('admin.pages.websettings.table.delete') }}"
                                >
                                    <img src="{{ asset('images/Trash.png') }}" alt="" aria-hidden="true" />
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (!empty($item->web_detail))
                        <div class="admin-wsCard__desc">{{ \Illuminate\Support\Str::limit($item->web_detail, 70) }}</div>
                    @endif

                    <div class="admin-wsCard__date">{{ optional($item->updated_at)->format('Y-m-d') }}</div>
                </div>
            @empty
                <div class="admin-muted" style="margin-top: 10px;">{{ __('admin.pages.websettings.table.empty') }}</div>
            @endforelse

            <div class="admin-dtFooter" style="padding: 14px 0 0;">
                <form method="GET" class="admin-dtFooter__left" id="websettings-per-page-form">
                    <span>{{ __('admin.pages.websettings.footer.rows_per_page') }}</span>
                    <select name="per_page" class="admin-dtFooter__select" onchange="document.getElementById('websettings-per-page-form').submit()">
                        @foreach ([10, 15, 25, 50] as $size)
                            <option value="{{ $size }}" @selected((int) ($perPage ?? 15) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    <span>{{ __('admin.pages.websettings.footer.of_rows', ['count' => number_format($setCount ?? 0)]) }}</span>
                    @foreach (request()->except('per_page', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                </form>

                <div class="admin-dtFooter__right">
                    {{ $sets->onEachSide(1)->links('vendor.pagination.admin') }}
                </div>
            </div>
        </section>

        <section class="admin-card admin-card--form admin-webdetailPage__form">
            <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
                <div class="admin-tagPage__title">{{ __('admin.pages.websettings.details') }}</div>
                @if (!empty($viewSet))
                    <a
                        class="admin-iconBtn"
                        href="{{ $clearPanelUrl }}"
                        aria-label="{{ __('admin.pages.websettings.close') }}"
                        title="{{ __('admin.pages.websettings.close') }}"
                    >
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        </svg>
                    </a>
                @endif
            </div>

            @if (!empty($viewSet))
                @php($landingUrl = !empty($viewSet->landing_image_path) ? asset('storage/' . ltrim($viewSet->landing_image_path, '/')) : null)
                @php($mapUrl = !empty($viewSet->seminar_map_image_path) ? asset('storage/' . ltrim($viewSet->seminar_map_image_path, '/')) : null)

                <div
                    class="admin-wsPreview"
                    style="margin-top: 12px; padding: 14px; border: 1px solid rgba(17,24,39,0.12); border-radius: 14px; background: #f9fafb;"
                >
                    <div style="display:flex; align-items:center; justify-content:space-between; gap: 10px; margin-bottom: 10px;">
                        <div style="font-weight: 900; font-size: 15px; color: rgba(17,24,39,0.92);">
                            {{ __('admin.pages.websettings.preview') }}
                        </div>
                        <div style="font-weight: 800; font-size: 13px; color: rgba(17,24,39,0.55);">
                            ID: {{ $viewSet->getKey() }}
                        </div>
                    </div>

                    <div class="admin-field">
                        <label class="admin-label">{{ __('admin.pages.websettings_form.fields.title') }}</label>
                        <div class="admin-input" style="display:flex; align-items:center; min-height: 42px; background:#fff;">
                            {{ $viewSet->web_title ?: '-' }}
                        </div>
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label">{{ __('admin.pages.websettings_form.fields.detail') }}</label>
                        <div class="admin-textarea" style="white-space: pre-wrap; min-height: 120px; background:#fff;">
                            {{ $viewSet->web_detail ?: '-' }}
                        </div>
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label">{{ __('admin.pages.websettings_form.fields.landing') }}</label>
                        @if ($landingUrl)
                            <img src="{{ $landingUrl }}" alt="" style="margin-top:8px; width: 100%; max-width: 420px; border-radius: 10px; border: 1px solid rgba(17,24,39,0.12);" />
                        @else
                            <div class="admin-muted" style="margin-top:6px;">-</div>
                        @endif
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label">{{ __('admin.pages.websettings_form.fields.map') }}</label>
                        @if ($mapUrl)
                            <img src="{{ $mapUrl }}" alt="" style="margin-top:8px; width: 100%; max-width: 420px; border-radius: 10px; border: 1px solid rgba(17,24,39,0.12);" />
                        @else
                            <div class="admin-muted" style="margin-top:6px;">-</div>
                        @endif
                    </div>
                </div>
            @else
                <div style="margin-top: 12px;">
                    <div style="font-weight: 900; font-size: 15px; color: rgba(17,24,39,0.92);">
                        {{ __('admin.pages.websettings.add_new') }}
                    </div>
                    <div class="admin-muted" style="margin-top:6px;">{{ __('admin.pages.websettings.hint_view') }}</div>
                </div>

                <form
                    method="POST"
                    enctype="multipart/form-data"
                    action="{{ route('admin.websettings.store') }}"
                    class="admin-form"
                    style="margin-top: 12px;"
                >
                    @csrf

                    <div class="admin-field">
                        <label class="admin-label" for="web_title">{{ __('admin.pages.websettings_form.fields.title') }}</label>
                        <input
                            id="web_title"
                            name="web_title"
                            class="admin-input @error('web_title') is-invalid @enderror"
                            value="{{ old('web_title') }}"
                            maxlength="150"
                        />
                        @error('web_title')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label" for="web_detail">{{ __('admin.pages.websettings_form.fields.detail') }}</label>
                        <textarea
                            id="web_detail"
                            name="web_detail"
                            class="admin-textarea @error('web_detail') is-invalid @enderror"
                            rows="6"
                        >{{ old('web_detail') }}</textarea>
                        @error('web_detail')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label" for="landing_image">{{ __('admin.pages.websettings_form.fields.landing') }}</label>
                    <div class="admin-file @error('landing_image') is-invalid @enderror">
                        <input
                            id="landing_image"
                            name="landing_image"
                            type="file"
                            accept="image/*"
                            class="admin-file__input"
                            data-file-input
                            data-placeholder="{{ __('admin.common.no_file_chosen') }}"
                        />
                        <label class="admin-file__btn" for="landing_image">{{ __('admin.common.choose_file') }}</label>
                        <span class="admin-file__name" data-file-name-for="landing_image">{{ __('admin.common.no_file_chosen') }}</span>
                    </div>
                        @error('landing_image')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-field" style="margin-top: 12px;">
                        <label class="admin-label" for="seminar_map_image">{{ __('admin.pages.websettings_form.fields.map') }}</label>
                    <div class="admin-file @error('seminar_map_image') is-invalid @enderror">
                        <input
                            id="seminar_map_image"
                            name="seminar_map_image"
                            type="file"
                            accept="image/*"
                            class="admin-file__input"
                            data-file-input
                            data-placeholder="{{ __('admin.common.no_file_chosen') }}"
                        />
                        <label class="admin-file__btn" for="seminar_map_image">{{ __('admin.common.choose_file') }}</label>
                        <span class="admin-file__name" data-file-name-for="seminar_map_image">{{ __('admin.common.no_file_chosen') }}</span>
                    </div>
                        @error('seminar_map_image')
                            <div class="admin-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display:flex; justify-content:center; margin-top: 14px;">
                        <button type="submit" class="admin-btn admin-btn--dark">
                            {{ __('admin.pages.websettings_form.save') }}
                        </button>
                    </div>
                </form>
            @endif
        </section>
    </div>
@endsection


