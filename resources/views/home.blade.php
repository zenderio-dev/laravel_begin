@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <section class="hero">
        <span class="eyebrow">Просто быть на связи</span>
        <h1>Ваше обращение.<br><span>Ничего лишнего.</span></h1>
        <p class="lead">Оставьте вопрос, предложение или отзыв. Мы сохраним вашу заявку, а вы сможете найти её в общем списке.</p>
        <div class="actions">
            <a class="button" href="{{ route('submissions.create') }}">Оставить заявку <span aria-hidden="true">↗</span></a>
            <a class="button secondary" href="{{ route('submissions.index') }}">Посмотреть все заявки</a>
        </div>
    </section>
    <section class="steps" aria-label="Как это работает">
        <article class="card"><span class="step-number">01</span><h2>Заполните форму</h2><p>Представьтесь, укажите email и расскажите, с чем обращаетесь.</p></article>
        <article class="card"><span class="step-number">02</span><h2>Отправьте заявку</h2><p>Проверим поля и подтвердим успешное сохранение.</p></article>
        <article class="card"><span class="step-number">03</span><h2>Откройте список</h2><p>Все отправленные обращения доступны на отдельной странице.</p></article>
    </section>
@endsection
