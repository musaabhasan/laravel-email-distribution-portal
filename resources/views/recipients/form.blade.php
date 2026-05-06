@extends('layouts.app')

@section('content')
  <div class="topline"><h2>Add Recipient</h2></div>
  <form class="panel" method="post" action="{{ route('recipients.store') }}">
    @csrf
    <div class="grid">
      <label>Email <input name="email" type="email" required value="{{ old('email', $recipient->email) }}"></label>
      <label>First name <input name="first_name" value="{{ old('first_name', $recipient->first_name) }}"></label>
      <label>Last name <input name="last_name" value="{{ old('last_name', $recipient->last_name) }}"></label>
      <label>Organization <input name="organization" value="{{ old('organization', $recipient->organization) }}"></label>
      <label>Job title <input name="job_title" value="{{ old('job_title', $recipient->job_title) }}"></label>
      <label>Consent source <input name="consent_source" required value="{{ old('consent_source', $recipient->consent_source) }}"></label>
    </div>
    <label>Segments
      <select name="groups[]" multiple>
        @foreach ($groups as $group)
          <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
      </select>
    </label>
    <button type="submit">Save Recipient</button>
  </form>
@endsection
