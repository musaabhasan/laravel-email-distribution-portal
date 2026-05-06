@extends('layouts.app')

@section('content')
  <div class="topline"><h2>{{ $recipient->displayName() }}</h2></div>
  <section class="panel">
    <p><strong>Email:</strong> {{ $recipient->email }}</p>
    <p><strong>Organization:</strong> {{ $recipient->organization ?: 'Not recorded' }}</p>
    <p><strong>Consent:</strong> {{ optional($recipient->consented_at)->format('Y-m-d H:i') ?? 'Missing' }}</p>
    <p><strong>Groups:</strong> {{ $recipient->groups->pluck('name')->join(', ') ?: 'None' }}</p>
  </section>
@endsection
