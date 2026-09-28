@extends('layouts.public')

@section('title', 'Exhibitor List')

@section('content')
    <div class="match-screen">
        <header class="match-header">
            <h1 class="match-title">出展者リスト</h1>
        </header>

        <section class="match-map">
            <div class="match-map__card">
                @if (!empty($mapImageUrl))
                    <img src="{{ $mapImageUrl }}" alt="Seminar map" class="match-map__img" />
                @else
                    <div class="match-map__placeholder">No map image</div>
                @endif
            </div>
        </section>

        <section class="match-result">
            <h2 class="match-subtitle">お客様にマッチした出展者</h2>

            @php
                $bg = $visitor->problemTag?->cssColor();
                $fg = $visitor->problemTag?->cssTextColor();
            @endphp
            <div class="match-pill">
                <span
                    class="match-pill__inner"
                    @if ($bg)
                        style="--pill-bg: {{ $bg }}; --pill-fg: {{ $fg ?: '#ffffff' }};"
                    @endif
                >{{ $visitor->problemTag?->tag_name ?: 'タグ未選択' }}</span>
            </div>

            <div class="match-list">
                @if (isset($exhibitors) && $exhibitors instanceof \Illuminate\Support\Collection && !$exhibitors->isEmpty())
                    @foreach ($exhibitors as $ex)
                        @php
                            $logo = $ex->ex_logo ?: '';
                            $logoUrl = null;
                            if (!empty($logo)) {
                                $logoUrl = \Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])
                                    ? $logo
                                    : asset('storage/' . ltrim($logo, '/'));
                            }

                            $website = $ex->ex_website ?: '';
                            $websiteHref = null;
                            if (!empty($website)) {
                                $websiteHref = \Illuminate\Support\Str::startsWith($website, ['http://', 'https://'])
                                    ? $website
                                    : 'https://' . $website;
                            }
                        @endphp

                        <article class="match-card">
                            <div class="match-card__logo">
                                @if (!empty($logoUrl))
                                    <img src="{{ $logoUrl }}" alt="{{ $ex->ex_companyName }} logo" />
                                @else
                                    <span class="match-card__logoText">{{ mb_substr($ex->ex_companyName, 0, 1) }}</span>
                                @endif
                            </div>

                            <div class="match-card__body">
                                <div class="match-card__name">{{ $ex->ex_companyName }}</div>

                                @if (!empty($websiteHref))
                                    <a class="match-card__link" href="{{ $websiteHref }}" target="_blank" rel="noopener noreferrer">
                                        {{ $websiteHref }}
                                    </a>
                                @else
                                    <div class="match-card__muted">-</div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="match-empty">
                        該当する出展者が見つかりませんでした
                    </div>
                @endif
            </div>
        </section>

        <footer class="match-footer">
            <a class="match-back" href="{{ route('visitor.register') }}">戻る</a>
        </footer>
    </div>
@endsection
