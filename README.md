# Заявки — v1.0

Учебное веб-приложение на Laravel. Форма принимает имя, email и текст обращения, проверяет данные на сервере и сохраняет каждую заявку в отдельный JSON-файл. Все заявки отображаются в таблице, начиная с новых.

## Запуск

Требуются PHP 8.3+ (для конкретных зависимостей из composer.lock — PHP 8.4.1+), Composer и расширения PHP, включая mbstring, openssl, fileinfo, dom и curl. На компьютере разработки используется PHP 8.5.4. Точные версии пакетов зафиксированы в `composer.lock`.

В PowerShell из каталога проекта:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan serve
```

Откройте http://127.0.0.1:8000. Копировать `.env.example` и генерировать ключ нужно только при первой установке. В текущей локальной копии `.env` и ключ уже созданы.

Если Composer не установлен в PATH, вместо `composer` используйте `php ПУТЬ_К_COMPOSER/composer.phar`. При создании этого проекта использована команда `composer create-project laravel/laravel Z:/dev/laravel-begin --prefer-dist --no-interaction --no-cache`.

Node.js, сборка Vite и подключение базы данных для этих страниц не нужны: стили находятся в `public/css/app.css`, сессии и кеш используют файлы.

## Настройки

В `.env` и `.env.example`:

- `APP_NAME="Заявки"` — название приложения.
- `APP_ENV=local`, `APP_DEBUG=true` — режим локальной разработки.
- `APP_TIMEZONE=Asia/Irkutsk` — часовой пояс.
- `APP_LOCALE=ru`, `APP_FALLBACK_LOCALE=ru` — локализация.
- `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` — работа без БД.
- `APP_KEY` — индивидуальный ключ шифрования, создаётся командой `php artisan key:generate` и хранится только в `.env`.

Для публикации приложения установите `APP_ENV=production`, `APP_DEBUG=false`. Файл `.env`, зависимости `vendor` и пользовательские JSON-файлы исключены из Git.

## Маршруты и контроллеры

| Метод | Адрес | Обработчик | Назначение |
|---|---|---|---|
| GET | `/` | `HomeController@index` | Главная страница |
| GET | `/submissions/create` | `SubmissionController@create` | Форма |
| POST | `/submissions` | `SubmissionController@store` | Проверка и сохранение |
| GET | `/submissions` | `SubmissionController@index` | Таблица всех заявок |

Маршруты заданы в `routes/web.php`, контроллеры — в `app/Http/Controllers`.

## Представления

- `resources/views/layouts/app.blade.php` — общий лейаут с `@yield`.
- `resources/views/partials/header.blade.php` — вставка с SVG-логотипом и меню.
- `resources/views/partials/footer.blade.php` — вставка с футером и версией.
- `home.blade.php`, `submissions/create.blade.php`, `submissions/index.blade.php` — страницы с `@extends` и `@section`.

Форма использует POST и `@csrf`. При ошибках сервер возвращает форму с сообщениями на русском и заполненными значениями (`old`). После успешной записи выполняется перенаправление на список и показывается сообщение об успехе.

## Валидация и хранение

- Имя: обязательно, строка, не более 100 символов.
- Email: обязательно, корректный email, не более 255 символов.
- Обращение: обязательно, строка от 10 до 5000 символов.

`SubmissionController@store` сохраняет данные через локальный диск Laravel в `storage/app/private/submissions/<UUID>.json`. UUID создаёт уникальное имя даже для одинаковых обращений. JSON содержит `id`, `name`, `email`, `message`, `created_at`; кириллица сохраняется читаемой. Папка создаётся автоматически при первой записи.

Данные находятся вне публичного каталога. Страница таблицы выводит все заявки; авторизация не входит в это учебное задание. Blade экранирует пользовательский текст. Нераспознаваемые или неполные JSON-записи пропускаются при чтении списка.

## Проверка

```powershell
php artisan test
php artisan route:list --except-vendor
php vendor/bin/pint --test
```

Тесты проверяют страницы, сохранение JSON и уникальные имена, ошибки валидации, сообщение об успехе, порядок таблицы, экранирование HTML и обработку отказа записи. Для файлов используются изолированные тестовые диски: реальные заявки не изменяются.

Ручная проверка: отправьте корректную заявку, проверьте сообщение и таблицу; затем отправьте пустую форму или неверный email. Для проверки именно серверной валидации отключите HTML-валидацию браузера или отправьте запрос программно.

## Версия и Git

Версия приложения — **1.0**, локальный Git-тег — **v1.0**. Версия в футере задана в `config/app.php`. Репозиторий GitHub и отправка коммитов подключаются отдельно.
