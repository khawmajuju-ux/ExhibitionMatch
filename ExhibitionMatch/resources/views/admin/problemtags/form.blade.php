@extends('layouts.admin')

@section('title', $mode === 'edit' ? __('admin.pages.problemtags_form.title_edit') : __('admin.pages.problemtags_form.title_create'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.nav.setting') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item">{{ __('admin.pages.problemtags.title') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">
            {{ $mode === 'edit' ? __('admin.pages.problemtags_form.title_edit') : __('admin.pages.problemtags_form.title_create') }}
        </span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">
                {{ $mode === 'edit' ? __('admin.pages.problemtags_form.title_edit') : __('admin.pages.problemtags_form.title_create') }}
            </h1>
            <div class="admin-muted">{{ __('admin.pages.problemtags_form.subtitle') }}</div>
        </div>
        <a class="admin-btn admin-btn--ghost" href="{{ route('admin.problemtags.index') }}">{{ __('admin.pages.problemtags_form.back') }}</a>
    </div>

    <section class="admin-card admin-card--form">
        <form method="POST" action="{{ $mode === 'edit' ? route('admin.problemtags.update', $tag) : route('admin.problemtags.store') }}" class="admin-form">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div class="admin-form__grid">
                <div class="admin-field">
                    <label class="admin-label" for="tag_name">{{ __('admin.pages.problemtags_form.fields.name') }}</label>
                    <input
                        id="tag_name"
                        name="tag_name"
                        class="admin-input @error('tag_name') is-invalid @enderror"
                        value="{{ old('tag_name', $tag->tag_name) }}"
                        maxlength="100"
                        required
                    />
                    @error('tag_name')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="tag_color">{{ __('admin.pages.problemtags_form.fields.color') }}</label>
                    <input
                        id="tag_color"
                        name="tag_color"
                        class="admin-input @error('tag_color') is-invalid @enderror"
                        value="{{ old('tag_color', $tag->tag_color) }}"
                        maxlength="20"
                        placeholder="#9CA3AF"
                    />
                    @error('tag_color')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field admin-field--full">
                    <label class="admin-label" for="tag_detail">{{ __('admin.pages.problemtags_form.fields.detail') }}</label>
                    <textarea
                        id="tag_detail"
                        name="tag_detail"
                        class="admin-textarea @error('tag_detail') is-invalid @enderror"
                        rows="6"
                    >{{ old('tag_detail', $tag->tag_detail) }}</textarea>
                    @error('tag_detail')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn admin-btn--primary">
                    {{ $mode === 'edit' ? __('admin.pages.problemtags_form.save_edit') : __('admin.pages.problemtags_form.save') }}
                </button>
            </div>
        </form>

        @if ($mode === 'edit')
            <form method="POST" action="{{ route('admin.problemtags.destroy', $tag) }}" class="admin-form__danger" onsubmit="return confirm('{{ __('admin.pages.problemtags_form.confirm_delete') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn--danger">{{ __('admin.pages.problemtags_form.delete') }}</button>
            </form>
        @endif
    </section>
@endsection


