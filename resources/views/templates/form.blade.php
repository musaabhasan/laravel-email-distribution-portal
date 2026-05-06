@extends('layouts.app')

@section('content')
  <div class="topline"><h2>{{ $template->exists ? 'Edit Template' : 'New Template' }}</h2></div>
  <form class="panel" method="post" action="{{ $template->exists ? route('templates.update', $template) : route('templates.store') }}">
    @csrf
    @if ($template->exists)
      @method('put')
    @endif
    <label>Name <input name="name" required value="{{ old('name', $template->name) }}"></label>
    <label>Subject <input name="subject" required value="{{ old('subject', $template->subject) }}"></label>
    <label>HTML body <textarea name="html_body" required>{{ old('html_body', $template->html_body) }}</textarea></label>
    <label>Plain text body <textarea name="text_body">{{ old('text_body', $template->text_body) }}</textarea></label>
    <label><input type="hidden" name="is_active" value="0"><span><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active ?? true))> Active</span></label>
    <button type="submit">Save Template</button>
  </form>
@endsection
