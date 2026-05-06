@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>{{ $template->name }}</h2>
    <a class="button secondary" href="{{ route('templates.edit', $template) }}">Edit</a>
  </div>
  <section class="panel">
    <p><strong>Subject:</strong> {{ $template->subject }}</p>
    <p><strong>Variables:</strong> {{ collect($template->variables ?? [])->join(', ') ?: 'None' }}</p>
    <hr>
    <div>{!! $template->html_body !!}</div>
  </section>
@endsection
