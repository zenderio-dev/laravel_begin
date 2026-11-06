<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function create(): View
    {
        return view('submissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $submission = [
            'id' => (string) Str::uuid(),
            ...$data,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        $saved = Storage::disk('local')->put(
            'submissions/'.$submission['id'].'.json',
            json_encode($submission, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        );

        if (! $saved) {
            return back()->withInput()->withErrors([
                'storage' => 'Не удалось сохранить заявку. Попробуйте ещё раз.',
            ]);
        }

        return to_route('submissions.index')->with('success', 'Заявка успешно сохранена!');
    }

    public function index(): View
    {
        $disk = Storage::disk('local');
        $submissions = collect($disk->files('submissions'))
            ->filter(fn (string $path) => str_ends_with($path, '.json'))
            ->map(fn (string $path) => json_decode($disk->get($path), true))
            ->filter(fn ($data) => is_array($data)
                && isset($data['id'], $data['name'], $data['email'], $data['message'], $data['created_at']))
            ->sortByDesc('created_at')
            ->values();

        return view('submissions.index', compact('submissions'));
    }
}
