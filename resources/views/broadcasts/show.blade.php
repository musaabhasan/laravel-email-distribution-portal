@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>{{ $broadcast->name }}</h2>
    <div class="actions">
      <form method="post" action="{{ route('broadcasts.approve', $broadcast) }}">@csrf<button type="submit">Approve</button></form>
      <form method="post" action="{{ route('broadcasts.queue', $broadcast) }}">@csrf<button type="submit" class="button secondary">Queue Recipients</button></form>
    </div>
  </div>
  <section class="grid">
    <article class="panel metric"><span>Status</span><strong>{{ $broadcast->status->value }}</strong></article>
    <article class="panel metric"><span>Queued recipients</span><strong>{{ number_format($broadcast->recipients_count) }}</strong></article>
    <article class="panel metric"><span>Scheduled</span><strong>{{ optional($broadcast->scheduled_at)->format('Y-m-d H:i') ?? 'Now' }}</strong></article>
  </section>
  <section class="panel">
    <h3>Configuration</h3>
    <p><strong>Template:</strong> {{ $broadcast->template->name }}</p>
    <p><strong>Sender:</strong> {{ $broadcast->from_name }} &lt;{{ $broadcast->from_email }}&gt;</p>
    <p><strong>Segments:</strong> {{ $broadcast->groups->pluck('name')->join(', ') }}</p>
  </section>
  <section class="panel">
    <h3>Recent Delivery Logs</h3>
    <table>
      <thead><tr><th>Recipient</th><th>Status</th><th>SMTP</th><th>Sent</th></tr></thead>
      <tbody>
        @foreach ($logs as $log)
          <tr><td>{{ $log->recipient_id }}</td><td>{{ $log->status }}</td><td>{{ $log->smtp_code }} {{ $log->smtp_response }}</td><td>{{ optional($log->sent_at)->format('Y-m-d H:i:s') }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </section>
@endsection
