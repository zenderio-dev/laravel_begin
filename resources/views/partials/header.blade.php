<a class="skip-link" href="#main">Перейти к содержимому</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Заявки — главная">
            <svg class="logo" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                <rect width="40" height="40" rx="12" fill="currentColor"/>
                <path d="M12 13h16v11H18l-6 5V13Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                <path d="M16 17h8M16 21h5" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Заявки<span class="brand-caption">Обратная связь</span></span>
        </a>
        <nav aria-label="Основное меню">
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Главная</a>
            <a href="{{ route('submissions.create') }}" @if(request()->routeIs('submissions.create')) aria-current="page" @endif>Новая заявка</a>
            <a href="{{ route('submissions.index') }}" @if(request()->routeIs('submissions.index')) aria-current="page" @endif>Все заявки</a>
        </nav>
    </div>
</header>
