@extends('layouts.public')

@section('title', 'Visitor Registration')

@section('content')
    <div class="reg-screen">
        <header class="reg-header">
            <h1 class="reg-title">申込</h1>
            <p class="reg-desc">
                {{ $introText ?: 'こちらに説明文が入ります。' }}
            </p>
        </header>

        @if ($errors->any())
            <div class="visitor-alert visitor-alert--error">
                <div class="visitor-alert__title">入力内容をご確認ください</div>
                <ul class="visitor-alert__list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('visitor.register.store') }}" class="reg-form" novalidate>
            @csrf

            <div class="reg-field">
                <label class="reg-label" for="visitor_name">氏名</label>
                <input
                    id="visitor_name"
                    name="visitor_name"
                    type="text"
                    class="reg-input @error('visitor_name') is-invalid @enderror"
                    value="{{ old('visitor_name') }}"
                    maxlength="150"
                    required
                    autocomplete="name"
                    placeholder="氏名"
                />
                @error('visitor_name')
                    <div class="visitor-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="reg-field">
                <label class="reg-label" for="visitor_company">会社名</label>
                <input
                    id="visitor_company"
                    name="visitor_company"
                    type="text"
                    class="reg-input @error('visitor_company') is-invalid @enderror"
                    value="{{ old('visitor_company') }}"
                    maxlength="150"
                    placeholder="会社名"
                />
                @error('visitor_company')
                    <div class="visitor-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="reg-field">
                <label class="reg-label" for="visitor_position">役職</label>
                <input
                    id="visitor_position"
                    name="visitor_position"
                    type="text"
                    class="reg-input @error('visitor_position') is-invalid @enderror"
                    value="{{ old('visitor_position') }}"
                    maxlength="100"
                    placeholder="役職"
                />
                @error('visitor_position')
                    <div class="visitor-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="reg-field">
                <label class="reg-label" for="visitor_contact">連絡先</label>
                <input
                    id="visitor_contact"
                    name="visitor_contact"
                    type="text"
                    class="reg-input @error('visitor_contact') is-invalid @enderror"
                    value="{{ old('visitor_contact') }}"
                    maxlength="100"
                    placeholder="連絡先"
                />
                @error('visitor_contact')
                    <div class="visitor-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="reg-field">
                <label class="reg-label" for="Tag_id">問題カテゴリ</label>
                <div class="reg-select-wrap">
                    <select id="Tag_id" name="Tag_id" class="reg-select @error('Tag_id') is-invalid @enderror">
                        <option value="">該当する問題をお選びください</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->Tag_ID }}" @selected(old('Tag_id') == $tag->Tag_ID)>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('Tag_id')
                    <div class="visitor-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="reg-actions">
                <button type="submit" class="reg-btn">登録する</button>
            </div>
        </form>
    </div>
@endsection


