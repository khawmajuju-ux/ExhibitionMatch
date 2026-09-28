@extends('layouts.admin')

@section('title', __('admin.pages.exhibitors.table.view'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.breadcrumbs.user') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item">{{ __('admin.nav.exhibitors') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.exhibitors.table.view') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">
                {{ __('admin.pages.exhibitors.table.view') }}: {{ $exhibitor->ex_companyName }}
            </h1>
            <div class="admin-muted">ID: {{ $exhibitor->Ex_ID }}</div>
        </div>
        <div style="display:flex; gap:10px;">
            <a class="admin-btn admin-btn--ghost" href="{{ route('admin.exhibitors.index') }}">Back</a>
            <a class="admin-btn admin-btn--primary" href="{{ route('admin.exhibitors.edit', $exhibitor) }}">{{ __('admin.pages.exhibitors.table.edit') }}</a>
        </div>
    </div>

    <section class="admin-card admin-card--form">
        <div class="admin-form" style="padding: 4px 0;">
            <div class="admin-form__grid">
                <div class="admin-field">
                    <div class="admin-label">Company</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $exhibitor->ex_companyName ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Problem Category</div>
                    @php($tag = $exhibitor->problemTag)
                    @php($tagName = $tag?->tag_name ?: '-')
                    @php($tagBg = $tag?->cssColor())
                    @php($tagFg = $tag?->cssTextColor())
                    <div class="admin-input" style="display:flex; align-items:center;">
                        <span
                            class="admin-dt__tag"
                            @if ($tagBg)
                                style="--tag-bg: {{ $tagBg }}; --tag-fg: {{ $tagFg ?: '#ffffff' }}; --tag-border: transparent;"
                            @endif
                        >{{ $tagName }}</span>
                    </div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Email</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $exhibitor->ex_companyEmail ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Phone</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $exhibitor->ex_companyPhone ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Website</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $exhibitor->ex_website ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Logo</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $exhibitor->ex_logo ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Created at</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ optional($exhibitor->created_at)->format('Y-m-d H:i') ?: '-' }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection

