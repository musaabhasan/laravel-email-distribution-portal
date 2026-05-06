@extends('layouts.app')

@section('content')
  <div class="topline"><h2>New Broadcast</h2></div>
  <form class="panel" method="post" action="{{ route('broadcasts.store') }}">
    @csrf
    <div class="grid">
      <label>Name <input name="name" required value="{{ old('name') }}"></label>
      <label>Template
        <select name="email_template_id" required>
          @foreach ($templates as $template)
            <option value="{{ $template->id }}">{{ $template->name }}</option>
          @endforeach
        </select>
      </label>
      <label>From email <input name="from_email" type="email" required value="{{ old('from_email', config('mail.from.address')) }}"></label>
      <label>From name <input name="from_name" value="{{ old('from_name', config('mail.from.name')) }}"></label>
      <label>Reply-to <input name="reply_to" type="email" value="{{ old('reply_to') }}"></label>
      <label>Scheduled at <input name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at') }}"></label>
    </div>
    <label>Segments
      <select name="groups[]" multiple required>
        @foreach ($groups as $group)
          <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
      </select>
    </label>
    <button type="submit">Schedule Broadcast</button>
  </form>
@endsection
