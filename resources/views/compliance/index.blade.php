@extends('layouts.app')

@section('content')
  <div class="topline"><h2>Compliance and Deliverability</h2></div>
  <section class="grid">
    <article class="panel metric"><span>Suppressed addresses</span><strong>{{ number_format($suppressionCount) }}</strong></article>
    <article class="panel metric"><span>DNS snapshots</span><strong>{{ number_format($snapshots->count()) }}</strong></article>
  </section>
  <section class="panel">
    <h3>Deliverability Snapshots</h3>
    <table>
      <thead><tr><th>Domain</th><th>Score</th><th>SPF</th><th>DKIM</th><th>DMARC</th><th>Checked</th></tr></thead>
      <tbody>
        @foreach ($snapshots as $snapshot)
          <tr>
            <td>{{ $snapshot->domain }}</td>
            <td>{{ $snapshot->score }}</td>
            <td>{{ $snapshot->spf_pass ? 'Pass' : 'Review' }}</td>
            <td>{{ $snapshot->dkim_pass ? 'Pass' : 'Review' }}</td>
            <td>{{ $snapshot->dmarc_pass ? 'Pass' : 'Review' }}</td>
            <td>{{ $snapshot->checked_at->format('Y-m-d H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>
  <section class="panel">
    <h3>Recent Audit Events</h3>
    <table>
      <thead><tr><th>Event</th><th>User</th><th>IP</th><th>Created</th></tr></thead>
      <tbody>
        @foreach ($recentAuditEvents as $event)
          <tr><td>{{ $event->event }}</td><td>{{ $event->user_id }}</td><td>{{ $event->ip_address }}</td><td>{{ $event->created_at->format('Y-m-d H:i:s') }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </section>
@endsection
