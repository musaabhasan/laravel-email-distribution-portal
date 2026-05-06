<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TemplateController extends Controller
{
    public function index(): View
    {
        return view('templates.index', [
            'templates' => EmailTemplate::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('templates.form', ['template' => new EmailTemplate]);
    }

    public function store(StoreTemplateRequest $request): RedirectResponse
    {
        EmailTemplate::query()->create($request->validated() + [
            'user_id' => $request->user()->id,
            'variables' => $this->extractVariables($request->string('html_body').' '.$request->string('subject')),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('templates.index')->with('status', 'Template saved.');
    }

    public function show(EmailTemplate $template): View
    {
        return view('templates.show', ['template' => $template]);
    }

    public function edit(EmailTemplate $template): View
    {
        return view('templates.form', ['template' => $template]);
    }

    public function update(StoreTemplateRequest $request, EmailTemplate $template): RedirectResponse
    {
        $template->update($request->validated() + [
            'variables' => $this->extractVariables($request->string('html_body').' '.$request->string('subject')),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('templates.index')->with('status', 'Template updated.');
    }

    public function destroy(EmailTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('templates.index')->with('status', 'Template deleted.');
    }

    private function extractVariables(string $content): array
    {
        preg_match_all('/{{\s*([A-Za-z0-9_.-]+)\s*}}/', $content, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }
}
