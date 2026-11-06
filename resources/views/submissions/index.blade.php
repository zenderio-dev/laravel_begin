@extends('layouts.app')

@section('title', 'Все заявки')

@section('content')
    <div class="page-heading heading-row">
        <div><span class="eyebrow">Общая история</span><h1>Все заявки <span class="count">{{ $submissions->count() }}</span></h1><p>От новых обращений к более ранним. Время — {{ config('app.timezone') }}.</p></div>
        <a class="button" href="{{ route('submissions.create') }}">Новая заявка <span aria-hidden="true">+</span></a>
    </div>
    @if ($submissions->isEmpty())
        <section class="card empty-state"><span class="eyebrow">Здесь пока тихо</span><h2>Заявок ещё нет</h2><p>Первое обращение появится здесь после отправки формы.</p><a class="button" href="{{ route('submissions.create') }}">Создать первую заявку</a></section>
    @else
        <div class="card table-wrap" role="region" aria-label="Список заявок" tabindex="0">
            <table>
                <caption class="sr-only">Полученные через форму заявки</caption>
                <thead><tr><th scope="col">№</th><th scope="col">Имя</th><th scope="col">Email</th><th scope="col">Обращение</th><th scope="col">Дата и время</th></tr></thead>
                <tbody>
                    @foreach ($submissions as $submission)
                        <tr><td class="row-number">{{ $loop->iteration }}</td><td>{{ $submission['name'] }}</td><td>{{ $submission['email'] }}</td><td class="message-cell">{{ $submission['message'] }}</td><td class="date-cell">{{ $submission['created_at'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
