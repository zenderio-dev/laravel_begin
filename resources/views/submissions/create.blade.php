@extends('layouts.app')

@section('title', 'Новая заявка')

@section('content')
    <div class="page-heading"><span class="eyebrow">Обратная связь</span><h1>Новая заявка</h1><p>Все поля обязательны. Имя, email и текст будут видны в общем списке заявок.</p></div>
    <section class="card form-card">
        @if ($errors->any())
            <div class="notice error" role="alert">
                <strong>Заявка не отправлена:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('submissions.store') }}">
            @csrf
            <div class="field">
                <label for="name">Ваше имя</label>
                <input id="name" name="name" value="{{ old('name') }}" maxlength="100" autocomplete="name" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                @error('name')<p class="field-error" id="name-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="email">Электронная почта</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" placeholder="name@example.com" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')<p class="field-error" id="email-error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="message">Текст обращения</label>
                <textarea id="message" name="message" rows="7" minlength="10" maxlength="5000" required aria-describedby="message-hint @error('message') message-error @enderror" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                <p class="hint" id="message-hint">От 10 до 5000 символов.</p>
                @error('message')<p class="field-error" id="message-error">{{ $message }}</p>@enderror
            </div>
            <div class="actions"><button class="button" type="submit">Отправить заявку <span aria-hidden="true">↗</span></button><a class="text-link" href="{{ route('submissions.index') }}">К списку заявок</a></div>
        </form>
    </section>
@endsection
