<?php

namespace Tests\Feature;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    public function test_pages_are_available_and_use_the_shared_layout(): void
    {
        Storage::fake('local');

        foreach (['/', '/submissions/create', '/submissions'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('Основное меню')
                ->assertSee('Версия 1.0');
        }

        $this->get('/submissions')->assertSee('Заявок ещё нет');
        $this->get('/submissions/create')->assertSee('name="_token"', false);
    }

    public function test_post_saves_json_with_unique_filenames_and_shows_success(): void
    {
        Storage::fake('local');
        $data = ['name' => 'Иван', 'email' => 'ivan@example.com', 'message' => 'Хочу узнать о вашем приложении.'];

        $this->post(route('submissions.store'), $data)
            ->assertRedirect(route('submissions.index'))
            ->assertSessionHas('success', 'Заявка успешно сохранена!');
        $this->get(route('submissions.index'))->assertSee('Заявка успешно сохранена!');
        $this->post(route('submissions.store'), $data)->assertRedirect(route('submissions.index'));

        $files = Storage::disk('local')->files('submissions');
        $this->assertCount(2, $files);

        foreach ($files as $file) {
            $this->assertStringEndsWith('.json', $file);
            $json = json_decode(Storage::disk('local')->get($file), true, 512, JSON_THROW_ON_ERROR);
            $this->assertSame($data['name'], $json['name']);
            $this->assertSame($data['email'], $json['email']);
            $this->assertSame($data['message'], $json['message']);
            $this->assertSame('submissions/'.$json['id'].'.json', $file);
            $this->assertNotEmpty($json['created_at']);
        }
    }

    #[DataProvider('invalidSubmissions')]
    public function test_invalid_data_is_rejected_without_creating_files(array $changes, string $field): void
    {
        Storage::fake('local');
        $data = array_replace([
            'name' => 'Иван', 'email' => 'ivan@example.com', 'message' => 'Достаточно длинное обращение.',
        ], $changes);

        $this->from(route('submissions.create'))->post(route('submissions.store'), $data)
            ->assertRedirect(route('submissions.create'))
            ->assertSessionHasErrors($field)
            ->assertSessionHasInput('email', $data['email']);

        $this->assertCount(0, Storage::disk('local')->allFiles());

    }

    public function test_validation_errors_are_visible_after_redirect(): void
    {
        Storage::fake('local');

        $this->followingRedirects()->from(route('submissions.create'))
            ->post(route('submissions.store'), [
                'name' => '', 'email' => 'ivan@example.com', 'message' => 'Достаточно длинное обращение.',
            ])->assertOk()->assertSee('Заявка не отправлена:')
            ->assertSee('Поле «Имя» обязательно для заполнения.');
    }

    public static function invalidSubmissions(): array
    {
        return [
            'empty name' => [['name' => ''], 'name'],
            'name too long' => [['name' => str_repeat('я', 101)], 'name'],
            'invalid email' => [['email' => 'invalid'], 'email'],
            'empty message' => [['message' => ''], 'message'],
            'short message' => [['message' => 'Коротко'], 'message'],
            'message too long' => [['message' => str_repeat('я', 5001)], 'message'],
        ];
    }

    public function test_table_shows_all_records_newest_first_and_escapes_html(): void
    {
        Storage::fake('local');
        $disk = Storage::disk('local');
        foreach ([['older', '2026-01-01 10:00:00'], ['newer', '2026-01-02 10:00:00']] as [$id, $date]) {
            $disk->put('submissions/'.$id.'.json', json_encode([
                'id' => $id, 'name' => $id, 'email' => $id.'@example.com',
                'message' => '<script>alert("test")</script>', 'created_at' => $date,
            ], JSON_THROW_ON_ERROR));
        }

        $this->get(route('submissions.index'))->assertOk()
            ->assertSeeInOrder(['newer@example.com', 'older@example.com'])
            ->assertSee('<script>alert("test")</script>')
            ->assertDontSee('<script>alert("test")</script>', false);
    }

    public function test_failed_write_shows_error_instead_of_success(): void
    {
        $disk = Mockery::mock(FilesystemAdapter::class);
        $disk->shouldReceive('put')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('local')->once()->andReturn($disk);

        $this->from(route('submissions.create'))->post(route('submissions.store'), [
            'name' => 'Иван', 'email' => 'ivan@example.com', 'message' => 'Достаточно длинное обращение.',
        ])->assertRedirect(route('submissions.create'))
            ->assertSessionHasErrors('storage')
            ->assertSessionMissing('success');
    }

    public function test_application_uses_the_agreed_locale_and_timezone(): void
    {
        $this->assertSame('ru', config('app.locale'));
        $this->assertSame('Asia/Irkutsk', config('app.timezone'));
        $this->assertNotEmpty(config('app.key'));
    }
}
