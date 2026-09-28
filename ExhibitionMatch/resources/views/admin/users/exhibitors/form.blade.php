@extends('layouts.admin')

@section('title', $mode === 'edit' ? __('admin.pages.exhibitors_form.title_edit') : __('admin.pages.exhibitors_form.title_create'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.breadcrumbs.user') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item">{{ __('admin.nav.exhibitors') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">
            {{ $mode === 'edit' ? __('admin.pages.exhibitors_form.title_edit') : __('admin.pages.exhibitors_form.title_create') }}
        </span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">
                {{ $mode === 'edit' ? __('admin.pages.exhibitors_form.title_edit') : __('admin.pages.exhibitors_form.title_create') }}
            </h1>
            <div class="admin-muted">{{ __('admin.pages.exhibitors_form.subtitle') }}</div>
        </div>
        <a class="admin-btn admin-btn--ghost" href="{{ route('admin.exhibitors.index') }}">{{ __('admin.pages.exhibitors_form.back') }}</a>
    </div>

    <section class="admin-card admin-card--form">
        <form method="POST" action="{{ $mode === 'edit' ? route('admin.exhibitors.update', $exhibitor) : route('admin.exhibitors.store') }}" class="admin-form">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div class="admin-form__grid">
                <div class="admin-field">
                    <label class="admin-label" for="ex_companyName">{{ __('admin.pages.exhibitors_form.fields.company') }}</label>
                    <input
                        id="ex_companyName"
                        name="ex_companyName"
                        class="admin-input @error('ex_companyName') is-invalid @enderror"
                        value="{{ old('ex_companyName', $exhibitor->ex_companyName) }}"
                        maxlength="150"
                        required
                    />
                    @error('ex_companyName')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="Tag_id">{{ __('admin.pages.exhibitors_form.fields.tag') }}</label>
                    <select id="Tag_id" name="Tag_id" class="admin-input @error('Tag_id') is-invalid @enderror">
                        <option value="">-</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->Tag_ID }}" @selected(old('Tag_id', $exhibitor->Tag_id) == $tag->Tag_ID)>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('Tag_id')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="ex_companyEmail">{{ __('admin.pages.exhibitors_form.fields.email') }}</label>
                    <input
                        id="ex_companyEmail"
                        name="ex_companyEmail"
                        class="admin-input @error('ex_companyEmail') is-invalid @enderror"
                        value="{{ old('ex_companyEmail', $exhibitor->ex_companyEmail) }}"
                        maxlength="150"
                    />
                    @error('ex_companyEmail')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="ex_companyPhone">{{ __('admin.pages.exhibitors_form.fields.phone') }}</label>
                    <input
                        id="ex_companyPhone"
                        name="ex_companyPhone"
                        class="admin-input @error('ex_companyPhone') is-invalid @enderror"
                        value="{{ old('ex_companyPhone', $exhibitor->ex_companyPhone) }}"
                        maxlength="30"
                    />
                    @error('ex_companyPhone')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="ex_website">{{ __('admin.pages.exhibitors_form.fields.website') }}</label>
                    <input
                        id="ex_website"
                        name="ex_website"
                        class="admin-input @error('ex_website') is-invalid @enderror"
                        value="{{ old('ex_website', $exhibitor->ex_website) }}"
                        maxlength="255"
                    />
                    @error('ex_website')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-field">
                    <label class="admin-label" for="ex_logo">{{ __('admin.pages.exhibitors_form.fields.logo') }}</label>
                    <input
                        id="ex_logo"
                        name="ex_logo"
                        class="admin-input @error('ex_logo') is-invalid @enderror"
                        value="{{ old('ex_logo', $exhibitor->ex_logo) }}"
                        maxlength="255"
                    />
                    @error('ex_logo')
                        <div class="admin-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn admin-btn--primary">
                    {{ $mode === 'edit' ? __('admin.pages.exhibitors_form.save_edit') : __('admin.pages.exhibitors_form.save') }}
                </button>
            </div>
        </form>

        @if ($mode === 'edit')
            <form method="POST" action="{{ route('admin.exhibitors.destroy', $exhibitor) }}" class="admin-form__danger" onsubmit="return confirm('{{ __('admin.pages.exhibitors_form.confirm_delete') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn--danger">{{ __('admin.pages.exhibitors_form.delete') }}</button>
            </form>
        @endif
    </section>
@endsection


