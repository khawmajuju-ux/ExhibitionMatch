@extends('layouts.admin')

@section('title', __('admin.pages.visitors.table.view'))

@section('topbar_left')
    <div class="admin-crumb">
        <span class="admin-crumb__item">{{ __('admin.breadcrumbs.user') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item">{{ __('admin.nav.visitors') }}</span>
        <span class="admin-crumb__sep">/</span>
        <span class="admin-crumb__item admin-crumb__item--active">{{ __('admin.pages.visitors.table.view') }}</span>
    </div>
@endsection

@section('content')
    <div class="admin-pagehead">
        <div>
            <h1 class="admin-title" style="margin: 0;">
                {{ __('admin.pages.visitors.table.view') }}: {{ $visitor->visitor_name }}
            </h1>
            <div class="admin-muted">ID: {{ $visitor->Visitor_ID }}</div>
        </div>
        <div style="display:flex; gap:10px;">
            <a class="admin-btn admin-btn--ghost" href="{{ route('admin.visitors.index') }}">Back</a>
        </div>
    </div>

    <section class="admin-card admin-card--form">
        <div class="admin-form" style="padding: 4px 0;">
            <div class="admin-form__grid">
                <div class="admin-field">
                    <div class="admin-label">Name</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $visitor->visitor_name ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Company</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $visitor->visitor_company ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Position</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $visitor->visitor_position ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Contact</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ $visitor->visitor_contact ?: '-' }}</div>
                </div>
                <div class="admin-field">
                    <div class="admin-label">Problem Category</div>
                    @php($tag = $visitor->problemTag)
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
                    <div class="admin-label">Created at</div>
                    <div class="admin-input" style="display:flex; align-items:center;">{{ optional($visitor->created_at)->format('Y-m-d H:i') ?: '-' }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection

