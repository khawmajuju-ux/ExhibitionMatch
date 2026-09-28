@extends('layouts.admin')

@section('title', $mode === 'edit' ? __('admin.pages.websettings_form.title_edit') : __('admin.pages.websettings_form.title_create'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.nav.setting') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item">{{ __('admin.pages.websettings.title') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">
            {{ $mode === 'edit' ? __('admin.pages.websettings_form.title_edit') : __('admin.pages.websettings_form.title_create') }}
        </span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin:0;">{{ $mode === 'edit' ? __('admin.pages.websettings_form.title_edit') : __('admin.pages.websettings_form.title_create') }}</h1>
            <div class="admin-muted">{{ __('admin.pages.websettings_form.subtitle') }}</div>
        </div>
        <a class="admin-btn admin-btn--ghost" href="{{ route('admin.websettings.index') }}">{{ __('admin.pages.websettings_form.back') }}</a>
    </div>

    <section class="admin-card admin-card--form">
        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ $mode === 'edit' ? route('admin.websettings.update', $set) : route('admin.websettings.store') }}"
            class="admin-form"
        >
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div class="admin-form__grid">
                <div class="admin-field">
                    <label class="admin-label" for="web_title">{{ __('admin.pages.websettings_form.fields.title') }}</label>
                    <input
                        id="web_title"
                        name="web_title"
                        class="admin-input @error('web_title') is-invalid @enderror"
                        value="{{ old('web_title', $set->web_title) }}"
                        maxlength="150"
                    />
                    @error('web_title')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field admin-field--full">
                    <label class="admin-label" for="web_detail">{{ __('admin.pages.websettings_form.fields.detail') }}</label>
                    <textarea
                        id="web_detail"
                        name="web_detail"
                        class="admin-textarea @error('web_detail') is-invalid @enderror"
                        rows="6"
                    >{{ old('web_detail', $set->web_detail) }}</textarea>
                    @error('web_detail')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
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
                    @if (!empty($set->landing_image_path))
                        <div class="admin-muted">{{ __('admin.pages.websettings_form.fields.current') }}: {{ $set->landing_image_path }}</div>
                    @endif
                    @error('landing_image')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
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
                    @if (!empty($set->seminar_map_image_path))
                        <div class="admin-muted">{{ __('admin.pages.websettings_form.fields.current') }}: {{ $set->seminar_map_image_path }}</div>
                    @endif
                    @error('seminar_map_image')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn admin-btn--primary">{{ $mode === 'edit' ? __('admin.pages.websettings_form.save_edit') : __('admin.pages.websettings_form.save') }}</button>
            </div>
        </form>

        @if ($mode === 'edit')
            <form method="POST" action="{{ route('admin.websettings.destroy', $set) }}" class="admin-form__danger" onsubmit="return confirm('{{ __('admin.pages.websettings_form.confirm_delete') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn--danger">{{ __('admin.pages.websettings_form.delete') }}</button>
            </form>
        @endif
    </section>
@endsection


